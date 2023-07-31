<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneratedImagesTable extends Migration
{
    public function up()
    {
        Schema::create('generated_images', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id'); // Use UUID data type
            $table->text('prompt');
            $table->string('image_url');
            $table->timestamps();

            $table
                ->foreign('user_id')
                ->references('user_id') // Reference the UUID column in the users table
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('generated_images');
    }
}
