<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            foreach (['menu_shop_name', 'menu_shop_status', 'menu_cart_status'] as $column) {
                if (Schema::hasColumn('menus', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('page_user_items', function (Blueprint $table) {
            foreach (['orders_page_title', 'invoice_page_title', 'reviews_page_title', 'wishlist_page_title'] as $column) {
                if (Schema::hasColumn('page_user_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->text('menu_shop_name')->nullable();
            $table->text('menu_shop_status')->nullable();
            $table->text('menu_cart_status')->nullable();
        });

        Schema::table('page_user_items', function (Blueprint $table) {
            $table->string('orders_page_title')->nullable();
            $table->string('invoice_page_title')->nullable();
            $table->string('reviews_page_title')->nullable();
            $table->string('wishlist_page_title')->nullable();
        });
    }
};
