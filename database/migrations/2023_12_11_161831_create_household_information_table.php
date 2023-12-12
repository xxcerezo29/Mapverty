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
        Schema::create('household_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student')->nullable()->references('id')->on('students')->constrained()->onDelete('cascade');
            $table->string('father_name');
            $table->string('father_occupation');
            $table->string('father_education');
            $table->string('father_contact_number');
            $table->string('mother_name');
            $table->string('mother_occupation');
            $table->string('mother_education');
            $table->string('mother_contact_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('household_information');
    }
};
