<?php namespace MoonWalkerz\Trustpilot\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMoonWalkerzTrustpilotReviews5 extends Migration
{
    public function up()
    {
        Schema::table('moonwalkerz_trustpilot_reviews', function($table)
        {
            $table->double('business_trustscore', 10, 0)->nullable()->unsigned(false)->default(0.0)->comment(null)->change();
            $table->double('business_stars', 10, 0)->nullable()->unsigned(false)->default(0.0)->comment(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('moonwalkerz_trustpilot_reviews', function($table)
        {
            $table->decimal('business_trustscore', 1, 1)->nullable()->unsigned(false)->default(0.0)->comment(null)->change();
            $table->decimal('business_stars', 1, 1)->nullable()->unsigned(false)->default(0.0)->comment(null)->change();
        });
    }
}
