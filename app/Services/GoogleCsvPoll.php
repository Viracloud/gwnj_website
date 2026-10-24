<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Reads Google Form results from a Google Sheet tab that was published to the web as CSV
 * (File → Share → Publish to web → choose the "Public" tab → CSV).
 *
 * Only aggregated counts leave this class: individual rows are never stored or shown.
 */
class GoogleCsvPoll
{
    private const MAX_BYTES = 5 * 1024 * 1024;

    /** Only published Google Sheets URLs are accepted (protects the server from being pointed elsewhere). */
    public static function isAllowedUrl(?string $url): bool
    {
        if (!$url) {
            return false;
        }
        $parts = parse_url($url);
        return ($parts['scheme'] ?? '') === 'https'
            && strtolower($parts['host'] ?? '') === 'docs.google.com'
            && str_starts_with($parts['path'] ?? '', '/spreadsheets/');
    }

    /**
     * Turn a normal "Publish to web" link into its CSV form when needed
     * (…/pubhtml → …/pub?output=csv).
     */
    public static function normaliseUrl(string $url): string
    {
        $url = trim($url);
        if (str_contains($url, '/pubhtml')) {
            $url = str_replace('/pubhtml', '/pub', $url);
        }
        if (str_contains($url, '/pub') && !str_contains($url, 'output=csv')) {
            $url .= (str_contains($url, '?') ? '&' : '?').'output=csv';
        }
        return $url;
    }

    /** @return array<int, array<int, string>> all rows, the first row being the column headers */
    public static function fetchRows(string $url): array
    {
        if (!self::isAllowedUrl($url)) {
            throw new RuntimeException('Not a published Google Sheets link.');
        }

        $response = Http::timeout(15)->withOptions(['allow_redirects' => ['max' => 5]])->get($url);
        if (!$response->successful()) {
            throw new RuntimeException('Google returned HTTP '.$response->status().'.');
        }
        $body = $response->body();
        if (strlen($body) > self::MAX_BYTES) {
            throw new RuntimeException('The published sheet is too large.');
        }
        if (stripos(ltrim($body), '<!doctype html') === 0 || stripos(ltrim($body), '<html') === 0) {
            throw new RuntimeException('The link returns a web page, not CSV. Publish the tab as "Comma-separated values (.csv)".');
        }

        $body = preg_replace('/^\xEF\xBB\xBF/', '', $body); // strip BOM
        $fh = fopen('php://temp', 'r+');
        fwrite($fh, $body);
        rewind($fh);
        $rows = [];
        while (($row = fgetcsv($fh, 0, ',', '"', '')) !== false) {
            if ($row === [null]) {
                continue; // blank line
            }
            $rows[] = array_map(fn ($v) => trim((string) $v), $row);
        }
        fclose($fh);

        if (!$rows) {
            throw new RuntimeException('The published sheet is empty.');
        }
        return $rows;
    }

    /** Column headers of the published tab (used by the admin column picker). */
    public static function headers(string $url): array
    {
        return self::fetchRows($url)[0];
    }

    /**
     * @param array $columns [['index' => int, 'label' => str, 'label_fa' => ?str, 'type' => single|multiple|rating], ...]
     */
    public static function aggregate(array $rows, array $columns): array
    {
        $data = array_slice($rows, 1);
        // A response row is any row with at least one non-empty cell.
        $data = array_values(array_filter($data, fn ($r) => count(array_filter($r, fn ($v) => $v !== '')) > 0));

        $out = ['total' => count($data), 'questions' => []];

        foreach ($columns as $col) {
            $idx = (int) $col['index'];
            $type = in_array($col['type'] ?? 'single', ['single', 'multiple', 'rating'], true) ? $col['type'] : 'single';
            $counts = [];
            $answered = 0;
            $ratingSum = 0;
            $ratingN = 0;

            foreach ($data as $r) {
                $val = $r[$idx] ?? '';
                if ($val === '') {
                    continue;
                }
                $answered++;
                if ($type === 'multiple') {
                    // Google Forms writes checkbox answers as "A, B, C".
                    foreach (array_filter(array_map('trim', explode(', ', $val)), 'strlen') as $v) {
                        $counts[$v] = ($counts[$v] ?? 0) + 1;
                    }
                } elseif ($type === 'rating' && is_numeric($val)) {
                    $n = (int) round((float) $val);
                    $counts[$n] = ($counts[$n] ?? 0) + 1;
                    $ratingSum += (float) $val;
                    $ratingN++;
                } else {
                    $counts[$val] = ($counts[$val] ?? 0) + 1;
                }
            }

            $options = [];
            if ($type === 'rating') {
                $max = max(5, $counts ? max(array_keys($counts)) : 5);
                $min = $counts ? min(1, min(array_keys($counts))) : 1;
                for ($i = $min; $i <= $max; $i++) {
                    $options[] = ['label' => (string) $i, 'label_fa' => (string) $i, 'count' => $counts[$i] ?? 0];
                }
            } else {
                arsort($counts);
                foreach ($counts as $label => $c) {
                    $options[] = ['label' => (string) $label, 'label_fa' => null, 'count' => $c];
                }
            }

            $out['questions'][] = [
                'label' => $col['label'] ?? ($rows[0][$idx] ?? ''),
                'label_fa' => $col['label_fa'] ?? null,
                'type' => $type,
                'answered' => $answered,
                'average' => $ratingN ? round($ratingSum / $ratingN, 1) : null,
                'options' => $options,
            ];
        }

        return $out;
    }
}
