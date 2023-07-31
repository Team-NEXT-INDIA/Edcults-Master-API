<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_id')->unique();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('profile_pic')->nullable();
            $table->boolean('logged_in')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Generate and assign UUID for existing users
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            $user->user_id = Uuid::uuid4()->toString();
            $user->save();
        }
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
