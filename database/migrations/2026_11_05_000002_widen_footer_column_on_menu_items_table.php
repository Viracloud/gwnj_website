<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Footer columns 3 and 4 ("Get involved", "Accountability") can now take menu items too. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->string('footer_column', 20)->default('about')->change();
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->enum('footer_column', ['about', 'work'])->default('about')->change();
        });
    }
};
