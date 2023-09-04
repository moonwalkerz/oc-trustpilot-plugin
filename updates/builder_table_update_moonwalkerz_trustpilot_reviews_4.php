<?php namespace MoonWalkerz\Trustpilot\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMoonWalkerzTrustpilotReviews4 extends Migration
{
    public function up()
    {
        Schema::table('moonwalkerz_trustpilot_reviews', function($table)
        {
            $table->decimal('business_trustscore', 1, 1)->change();
            $table->decimal('business_stars', 1, 1)->change();
        });
    }
    
    public function down()
    {
        Schema::table('moonwalkerz_trustpilot_reviews', function($table)
        {
            $table->decimal('business_trustscore', 10, 0)->change();
            $table->decimal('business_stars', 10, 0)->change();
        });
    }
}
