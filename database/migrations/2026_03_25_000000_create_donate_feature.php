<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Donations received through the public "Donate" page.
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('donor_name');
            $table->string('donor_email');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('USD');
            $table->text('message')->nullable();
            $table->string('payment_method')->default('PayPal');
            $table->string('transaction_id')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamps();
        });

        // Editable content for the "Donate" page (same pattern as the other page_*_items tables).
        Schema::create('page_donate_items', function (Blueprint $table) {
            $table->id();
            $table->string('page_title')->nullable();
            $table->string('heading')->nullable();
            $table->longText('description')->nullable();
            $table->string('preset_amounts')->nullable();
            $table->decimal('goal_amount', 12, 2)->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_meta_description')->nullable();
            $table->timestamps();
        });

        // PayPal API credentials, configurable from the admin panel.
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'paypal_mode')) {
                $table->string('paypal_mode')->default('sandbox')->nullable();
            }
            if (!Schema::hasColumn('settings', 'paypal_client_id')) {
                $table->text('paypal_client_id')->nullable();
            }
            if (!Schema::hasColumn('settings', 'paypal_client_secret')) {
                $table->text('paypal_client_secret')->nullable();
            }
            if (!Schema::hasColumn('settings', 'paypal_currency')) {
                $table->string('paypal_currency')->default('USD')->nullable();
            }
        });

        // Nav-menu entry for the Donate page.
        Schema::table('menus', function (Blueprint $table) {
            if (!Schema::hasColumn('menus', 'menu_donate_name')) {
                $table->text('menu_donate_name')->nullable();
            }
            if (!Schema::hasColumn('menus', 'menu_donate_status')) {
                $table->text('menu_donate_status')->nullable();
            }
        });

        // Seed the single content row so the admin/front controllers always find id = 1.
        if (DB::table('page_donate_items')->where('id', 1)->doesntExist()) {
            DB::table('page_donate_items')->insert([
                'id' => 1,
                'page_title' => 'Support Our Social Work',
                'heading' => 'Your donation powers change in our community',
                'description' => 'We run social programs for education, relief and community development. '
                    . 'Every contribution — large or small — directly funds this work. '
                    . 'Choose an amount below and complete your secure donation through PayPal.',
                'preset_amounts' => '10,25,50,100,250',
                'goal_amount' => null,
                'seo_title' => 'Donate — Support Our Social Activities',
                'seo_meta_description' => 'Make a secure online donation via PayPal to support our social and community programs.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Default values on the existing single-row tables.
        DB::table('settings')->where('id', 1)->update([
            'paypal_mode' => DB::raw("COALESCE(paypal_mode, 'sandbox')"),
            'paypal_currency' => DB::raw("COALESCE(paypal_currency, 'USD')"),
        ]);

        DB::table('menus')->where('id', 1)->update([
            'menu_donate_name' => DB::raw("COALESCE(menu_donate_name, 'Donate')"),
            'menu_donate_status' => DB::raw("COALESCE(menu_donate_status, 'Show')"),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn(['menu_donate_name', 'menu_donate_status']);
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['paypal_mode', 'paypal_client_id', 'paypal_client_secret', 'paypal_currency']);
        });

        Schema::dropIfExists('page_donate_items');
        Schema::dropIfExists('donations');
    }
};
