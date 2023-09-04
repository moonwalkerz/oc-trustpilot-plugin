<?php namespace MoonWalkerz\Trustpilot\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMoonWalkerzTrustpilotReviews2 extends Migration
{
    public function up()
    {
        Schema::table('moonwalkerz_trustpilot_reviews', function($table)
        {
            $table->string('language')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('moonwalkerz_trustpilot_reviews', function($table)
        {
            $table->dropColumn('language');
        });
    }
}
