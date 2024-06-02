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
        Schema::create('unconfirmed_manuals', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('manual_name', 300);
            $table->string('description', 500);
            $table->string('author_email', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unconfirmed_manuals');
    }
};
