<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Indexes the database was missing: reference columns that are looked up on every page view
 * (comments of a post, awards of a member ...) and unique slugs for posts, events, members and
 * post categories (resolve_slug() already keeps them unique; the index makes it a guarantee).
 */
return new class extends Migration
{
    private array $indexes = [
        ['campaigns', 'campaign_category_id'],
        ['comments', 'post_id'],
        ['event_faqs', 'event_id'],
        ['member_awards', 'member_id'],
        ['replies', 'comment_id'],
        ['replies', 'post_id'],
    ];

    private array $uniques = ['posts', 'events', 'members', 'post_categories'];

    public function up(): void
    {
        foreach ($this->indexes as [$table, $column]) {
            if (Schema::hasColumn($table, $column) && !Schema::hasIndex($table, [$column])) {
                Schema::table($table, fn (Blueprint $t) => $t->index($column));
            }
        }
        foreach ($this->uniques as $table) {
            if (Schema::hasColumn($table, 'slug') && !Schema::hasIndex($table, ['slug'], 'unique')) {
                Schema::table($table, fn (Blueprint $t) => $t->unique('slug'));
            }
        }
    }

    public function down(): void
    {
        foreach ($this->uniques as $table) {
            if (Schema::hasIndex($table, $table.'_slug_unique')) {
                Schema::table($table, fn (Blueprint $t) => $t->dropUnique($table.'_slug_unique'));
            }
        }
        foreach ($this->indexes as [$table, $column]) {
            if (Schema::hasIndex($table, $table.'_'.$column.'_index')) {
                Schema::table($table, fn (Blueprint $t) => $t->dropIndex($table.'_'.$column.'_index'));
            }
        }
    }
};
