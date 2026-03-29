<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('placeholder_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->unique();
            $table->string('name');
            $table->string('username');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });

        Schema::create('placeholder_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placeholder_user_id')->constrained('placeholder_users')->cascadeOnDelete();
            $table->string('street')->nullable();
            $table->string('suite')->nullable();
            $table->string('city')->nullable();
            $table->string('zipcode')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->timestamps();

            $table->unique('placeholder_user_id');
        });

        Schema::create('placeholder_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placeholder_user_id')->constrained('placeholder_users')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('catch_phrase')->nullable();
            $table->string('bs')->nullable();
            $table->timestamps();

            $table->unique('placeholder_user_id');
        });

        Schema::create('placeholder_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->unique();
            $table->foreignId('placeholder_user_id')->constrained('placeholder_users')->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('placeholder_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->unique();
            $table->foreignId('placeholder_post_id')->constrained('placeholder_posts')->cascadeOnDelete();
            $table->string('name');
            $table->string('email');
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('placeholder_albums', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->unique();
            $table->foreignId('placeholder_user_id')->constrained('placeholder_users')->cascadeOnDelete();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('placeholder_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->unique();
            $table->foreignId('placeholder_album_id')->constrained('placeholder_albums')->cascadeOnDelete();
            $table->string('title');
            $table->string('url');
            $table->string('thumbnail_url');
            $table->timestamps();
        });

        Schema::create('placeholder_todos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->unique();
            $table->foreignId('placeholder_user_id')->constrained('placeholder_users')->cascadeOnDelete();
            $table->string('title');
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placeholder_todos');
        Schema::dropIfExists('placeholder_photos');
        Schema::dropIfExists('placeholder_albums');
        Schema::dropIfExists('placeholder_comments');
        Schema::dropIfExists('placeholder_posts');
        Schema::dropIfExists('placeholder_companies');
        Schema::dropIfExists('placeholder_addresses');
        Schema::dropIfExists('placeholder_users');
    }
};
