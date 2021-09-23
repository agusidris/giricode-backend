<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->boolean('featured')->default(0);
            $table->enum('status', ['active', 'inactive', 'deleted', 'published'])->default('active');
            $table->string('image');
            $table->string('title');
            $table->string('slug');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('content');
            $table->text('description');
            $table->timestamps();

            //relationship category
            $table->foreign('category_id')->references('id')->on('categories');

            // relationship user
            $table->foreign('user_id')->references('id')->on('users');
        });

        //create pivot table post_tag
        Schema::create('post_tag', function (Blueprint $table) {
            $table->integer('post_id');
            $table->integer('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
