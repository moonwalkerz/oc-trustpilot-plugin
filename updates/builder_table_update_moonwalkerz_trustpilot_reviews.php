<?php namespace MoonWalkerz\Trustpilot\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMoonWalkerzTrustpilotReviews extends Migration
{
    public function up()
    {
        Schema::table('moonwalkerz_trustpilot_reviews', function($table)
        {
            $table->string('consumer_id')->nullable();
            $table->string('consumer_name')->nullable();
            $table->integer('consumer_reviews')->nullable();
            $table->string('consumer_avatar')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('moonwalkerz_trustpilot_reviews', function($table)
        {
            $table->dropColumn('consumer_id');
            $table->dropColumn('consumer_name');
            $table->dropColumn('consumer_reviews');
            $table->dropColumn('consumer_avatar');
        });
    }
}
