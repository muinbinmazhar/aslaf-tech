<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_links', function (Blueprint $table) {
            $table->id();
            $table->string('type', 40);
            $table->string('display_name');
            $table->text('value');
            $table->string('supporting_text', 500)->nullable();
            $table->string('icon_key', 30)->nullable();
            $table->text('link_url')->nullable();
            $table->boolean('show_on_homepage')->default(false);
            $table->boolean('show_in_footer')->default(false);
            $table->boolean('show_in_header')->default(false);
            $table->boolean('is_visible')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('legacy_key', 40)->nullable()->unique();
            $table->timestamps();

            $table->index(['is_visible', 'sort_order']);
            $table->index('type');
        });

        app(\App\Actions\ImportLegacyContacts::class)->handle();
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_links');
    }
};
