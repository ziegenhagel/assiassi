<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->text("title");
            $table->integer("meal_vegans");
            $table->integer("meal_vegetarians");
            $table->integer("meal_omnivores");
            $table->integer("week");
            $table->integer("year");
            $table->text("meal_annotation");
            $table->char("letter",1);
            $table->boolean("is_special_course");
            $table->enum("sd_status",["queue","process","ready"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
