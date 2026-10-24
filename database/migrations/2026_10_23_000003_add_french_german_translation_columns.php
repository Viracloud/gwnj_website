<?php

use App\Support\LocaleColumns;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Empty French (_fr) and German (_de) translation columns next to every Dari (_fa)
     * column, so the two languages can be switched on later without a database change.
     * Nothing is shown on the website until they are enabled in .env (SITE_LOCALES).
     */
    public function up(): void
    {
        LocaleColumns::add('fr');
        LocaleColumns::add('de');
    }

    public function down(): void
    {
        LocaleColumns::drop('fr');
        LocaleColumns::drop('de');
    }
};
