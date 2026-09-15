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
        Schema::dropIfExists('service_faqs');
        Schema::dropIfExists('page_service_items');
        Schema::dropIfExists('services');

        Schema::dropIfExists('project_faqs');
        Schema::dropIfExists('page_project_items');
        Schema::dropIfExists('projects');

        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('member_skills');

        // Orphaned e-commerce tables left behind after the shop feature was
        // removed from the codebase; no models/controllers reference them.
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('product_photos');
        Schema::dropIfExists('product_specifications');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('products');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('shipping_methods');
        Schema::dropIfExists('page_cart_items');
        Schema::dropIfExists('page_shop_items');

        if (Schema::hasTable('menus')) {
            Schema::table('menus', function (Blueprint $table) {
                foreach ([
                    'menu_service_name', 'menu_service_status', 'menu_service_name_sv',
                    'menu_project_name', 'menu_project_status', 'menu_project_name_sv',
                    // Orphaned columns unrelated to any current feature.
                    'menu_categories_group_name', 'menu_categories_group_name_sv',
                ] as $column) {
                    if (Schema::hasColumn('menus', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('page_home_items')) {
            Schema::table('page_home_items', function (Blueprint $table) {
                foreach ([
                    'service_subheading', 'service_heading', 'service_text', 'service_status',
                    'service_subheading_sv', 'service_heading_sv', 'service_text_sv',
                    'project_subheading', 'project_heading', 'project_text', 'project_status',
                    'project_subheading_sv', 'project_heading_sv', 'project_text_sv',
                    'testimonial_subheading', 'testimonial_heading', 'testimonial_status',
                    'testimonial_subheading_sv', 'testimonial_heading_sv',
                ] as $column) {
                    if (Schema::hasColumn('page_home_items', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('page_about_items')) {
            Schema::table('page_about_items', function (Blueprint $table) {
                foreach ([
                    'service_subheading', 'service_heading', 'service_text', 'service_status',
                    'service_subheading_sv', 'service_heading_sv', 'service_text_sv',
                    'testimonial_subheading', 'testimonial_heading', 'testimonial_status',
                    'testimonial_subheading_sv', 'testimonial_heading_sv',
                ] as $column) {
                    if (Schema::hasColumn('page_about_items', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentional one-way cleanup: Services, Projects, Testimonials and
        // MemberSkill were construction-template features removed for GWJN.
    }
};
