<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialLinksToWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Social / contact links shown on the storefront (header, footer, contact).
     *
     * @return void
     */
    public function up()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->string('whatsapp_no', 30)->nullable()->after('phone_number');
            $table->string('facebook_link')->nullable()->after('email');
            $table->string('twitter_link')->nullable()->after('facebook_link');
            $table->string('instagram_link')->nullable()->after('twitter_link');
            $table->string('linkedin_link')->nullable()->after('instagram_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_no',
                'facebook_link',
                'twitter_link',
                'instagram_link',
                'linkedin_link',
            ]);
        });
    }
}
