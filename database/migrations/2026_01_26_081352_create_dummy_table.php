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
        Schema::create('dummy', function (Blueprint $table) {
            $table->id();
            $table->string('dummy_no');
            $table->string('dummy_name');
            $table->bigInteger('dummy_int')->nullable();
            $table->string('dummy_type')->nullable();
            $table->string('dummy_label')->nullable();
            $table->timestamp('dummy_date')->nullable();
            $table->string('dummy_price')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dummy');
    }
};
