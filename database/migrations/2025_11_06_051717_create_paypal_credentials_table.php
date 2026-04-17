<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('paypal_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('paypal_method')->default('sandbox'); 
            $table->string('client_id');
            $table->text('client_secret');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paypal_credentials');
    }
};
