<?php namespace MoonWalkerz\Trustpilot\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMoonWalkerzTrustpilotReviews extends Migration
{
    public function up()
    {
        Schema::create('moonwalkerz_trustpilot_reviews', function($table)
        {
            $table->increments('id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->string('tp_id')->nullable();
            $table->string('title')->nullable();
            $table->dateTime('date')->nullable();
            $table->text('text')->nullable();
            $table->boolean('visible')->default(1);
            $table->integer('rating')->default(0);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('moonwalkerz_trustpilot_reviews');
    }
}
