@php
    // Error pages are often shown before the language middleware runs (unknown address) or when
    // the database is down, so they take the language from the address and need nothing else.
    $err_locale = request()->segment(1);
    if (!in_array($err_locale, ['en', 'fa', 'fr', 'de'], true)) {
        $err_locale = 'en';
    }
    $err_rtl = $err_locale === 'fa';
    $texts = [
        404 => [
            'en' => ['Page not found', 'The page you are looking for does not exist or has been moved.'],
            'fa' => ['صفحه پیدا نشد', 'صفحه‌ای که دنبال آن هستید وجود ندارد یا جابه‌جا شده است.'],
            'fr' => ['Page introuvable', 'La page que vous cherchez n’existe pas ou a été déplacée.'],
            'de' => ['Seite nicht gefunden', 'Die gesuchte Seite existiert nicht oder wurde verschoben.'],
        ],
        403 => [
            'en' => ['Access denied', 'You do not have permission to open this page.'],
            'fa' => ['دسترسی ممکن نیست', 'شما اجازهٔ دیدن این صفحه را ندارید.'],
            'fr' => ['Accès refusé', 'Vous n’avez pas l’autorisation d’ouvrir cette page.'],
            'de' => ['Zugriff verweigert', 'Sie haben keine Berechtigung, diese Seite zu öffnen.'],
        ],
        419 => [
            'en' => ['Page expired', 'The form was open for too long. Please go back, reload the page and try again.'],
            'fa' => ['صفحه منقضی شد', 'فرم مدت زیادی باز مانده بود. لطفاً برگردید، صفحه را دوباره بارگذاری و دوباره تلاش کنید.'],
            'fr' => ['Page expirée', 'Le formulaire est resté ouvert trop longtemps. Revenez en arrière, rechargez la page et réessayez.'],
            'de' => ['Seite abgelaufen', 'Das Formular war zu lange geöffnet. Bitte gehen Sie zurück, laden Sie die Seite neu und versuchen Sie es erneut.'],
        ],
        429 => [
            'en' => ['Too many attempts', 'Please wait a minute and try again.'],
            'fa' => ['تلاش‌های زیاد', 'لطفاً یک دقیقه صبر کنید و دوباره تلاش کنید.'],
            'fr' => ['Trop de tentatives', 'Veuillez patienter une minute et réessayer.'],
            'de' => ['Zu viele Versuche', 'Bitte warten Sie eine Minute und versuchen Sie es erneut.'],
        ],
        500 => [
            'en' => ['Something went wrong', 'An error occurred on our side. Please try again in a few minutes.'],
            'fa' => ['خطایی رخ داد', 'مشکلی در سمت ما پیش آمد. لطفاً چند دقیقهٔ دیگر دوباره تلاش کنید.'],
            'fr' => ['Une erreur est survenue', 'Un problème est survenu de notre côté. Veuillez réessayer dans quelques minutes.'],
            'de' => ['Etwas ist schiefgelaufen', 'Bei uns ist ein Fehler aufgetreten. Bitte versuchen Sie es in einigen Minuten erneut.'],
        ],
        503 => [
            'en' => ['Back soon', 'The website is being updated. Please come back in a few minutes.'],
            'fa' => ['به‌زودی برمی‌گردیم', 'وب‌سایت در حال به‌روزرسانی است. لطفاً چند دقیقهٔ دیگر برگردید.'],
            'fr' => ['De retour bientôt', 'Le site est en cours de mise à jour. Revenez dans quelques minutes.'],
            'de' => ['Gleich wieder da', 'Die Website wird gerade aktualisiert. Bitte kommen Sie in einigen Minuten wieder.'],
        ],
    ];
    [$err_title, $err_text] = $texts[$code][$err_locale] ?? $texts[500][$err_locale];
    $err_home = ['en' => 'Go to the home page', 'fa' => 'رفتن به صفحهٔ اصلی', 'fr' => 'Aller à l’accueil', 'de' => 'Zur Startseite'][$err_locale];
    $err_digits = $err_rtl ? strtr((string) $code, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $code;
@endphp
<!DOCTYPE html>
<html lang="{{ $err_locale }}" dir="{{ $err_rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $err_title }} - GWJN</title>
    <style>
        :root{--plum:#2a0f5c;--brand:#7c3aed;--ink:#1d1830;--muted:#625c75}
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;display:grid;place-items:center;padding:24px 16px;background:#f7f4fd;color:var(--ink);
            font-family:{{ $err_rtl ? 'Vazirmatn,Tahoma,' : '' }}Inter,system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif}
        main{width:min(520px,100%);text-align:center;background:#fff;border:1px solid #e7defa;border-radius:18px;padding:40px 28px;
            box-shadow:0 20px 50px -30px rgba(42,15,92,.45)}
        .code{font-size:clamp(3rem,12vw,4.5rem);font-weight:800;line-height:1;color:var(--brand);margin:0 0 12px}
        h1{font-size:1.4rem;margin:0 0 10px}
        p{margin:0 0 26px;color:var(--muted);line-height:{{ $err_rtl ? '1.9' : '1.6' }}}
        a{display:inline-block;padding:.7rem 1.4rem;border-radius:10px;background:var(--brand);color:#fff;text-decoration:none;font-weight:700}
        a:hover{background:var(--plum)}
        a:focus-visible{outline:3px solid var(--brand);outline-offset:3px}
    </style>
</head>
<body>
<main>
    <div class="code" aria-hidden="true">{{ $err_digits }}</div>
    <h1>{{ $err_title }}</h1>
    <p>{{ $err_text }}</p>
    <a href="{{ url('/'.$err_locale) }}">{{ $err_home }}</a>
</main>
</body>
</html>
