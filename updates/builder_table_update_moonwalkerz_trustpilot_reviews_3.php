<?php namespace MoonWalkerz\Trustpilot\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMoonWalkerzTrustpilotReviews3 extends Migration
{
    public function up()
    {
        Schema::table('moonwalkerz_trustpilot_reviews', function($table)
        {
            $table->integer('business_reviews')->nullable()->default(0);
            $table->decimal('business_trustscore', 10, 0)->nullable()->default(0);
            $table->decimal('business_stars', 10, 0)->nullable()->default(0);
            $table->string('business_name')->nullable();
            $table->string('business_image')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('moonwalkerz_trustpilot_reviews', function($table)
        {
            $table->dropColumn('business_reviews');
            $table->dropColumn('business_trustscore');
            $table->dropColumn('business_stars');
            $table->dropColumn('business_name');
            $table->dropColumn('business_image');
        });
    }
}
