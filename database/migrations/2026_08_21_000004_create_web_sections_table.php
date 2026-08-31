<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Which blocks a website's landing page is built from, in what order, and
     * whether each one shows on the page and in the navigation.
     *
     * section_key names the Blade partial that renders the block
     * (resources/views/pages/website2/sections/<key>.blade.php), so a row can
     * only ever point at a block that exists in code. That is also why the
     * admin edits these rows but does not create or delete them.
     *
     * A section can be on the page but out of the menu (or the reverse), so the
     * two flags are separate.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id')->index();

            // Matches the partial's filename; unique per website.
            $table->string('section_key', 60);

            // What the admin list calls it, and what the menu shows.
            $table->string('section_name', 100);
            $table->string('nav_label', 60)->nullable();

            // In-page anchor, e.g. "products" for href="#products".
            $table->string('anchor', 60)->nullable();

            $table->integer('order')->default(0)->index();

            $table->boolean('show_in_page')->default(true);
            $table->boolean('show_in_nav')->default(true);

            $table->text('remark')->nullable();

            $table->string('created_by', 60)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_by', 60)->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();

            $table->unique(['website_id', 'section_key'], 'web_sections_website_key_UN');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_sections');
    }
}
