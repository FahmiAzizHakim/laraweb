<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * The "About" section of a public site, one row per block. Previously the
     * section was hard-coded in the Blade view; now a website can have several
     * blocks and the site renders them in `order` (then id), so the first row
     * is the one that used to be there.
     *
     * about_content is plain text: blank lines separate paragraphs when it is
     * rendered, so nothing a content editor types can inject markup.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_id')->nullable()->index();

            $table->string('about_title');
            $table->string('about_subtitle')->nullable();
            $table->text('about_content')->nullable();
            $table->string('about_image')->nullable();

            // Display order; the lowest is the site's main "about".
            $table->integer('order')->default(0)->index();

            $table->boolean('is_active')->default(true);

            $table->string('created_by', 60)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_by', 60)->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abouts');
    }
}
