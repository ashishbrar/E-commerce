<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stripe_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('stripe_key');
            $table->text('stripe_secret');
            $table->enum('mode', ['test', 'live'])->default('test');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_credentials');
    }
};