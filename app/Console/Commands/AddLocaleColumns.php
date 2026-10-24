<?php

namespace App\Console\Commands;

use App\Support\LocaleColumns;
use Illuminate\Console\Command;

/**
 * Prepare the database for another language later on, e.g.:  php artisan locale:columns es
 * (French and German columns already exist.) Then add the language to config/locales.php.
 */
class AddLocaleColumns extends Command
{
    protected $signature = 'locale:columns {code : Two-letter language code, e.g. es}';

    protected $description = 'Create the translation columns (<field>_<code>) for a new website language';

    public function handle(): int
    {
        $code = strtolower($this->argument('code'));
        $added = LocaleColumns::add($code);
        $this->info("Added $added translation columns for '$code'.");
        return self::SUCCESS;
    }
}
