<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_series', function (Blueprint $table) {
            $table->id();
            $table->boolean('featured')->default(0);
            $table->enum('status', ['active', 'inactive', 'deleted', 'published'])->default('active');
            $table->string('image');
            $table->string('title');
            $table->string('slug');
            $table->text('description');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // relationship user
            $table->foreign('user_id')->references('id')->on('users');
        });


        //create pivot table post_tag
        Schema::create('post_post_series', function (Blueprint $table) {
            $table->integer('post_series_id');
            $table->integer('post_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_series');
    }
}
