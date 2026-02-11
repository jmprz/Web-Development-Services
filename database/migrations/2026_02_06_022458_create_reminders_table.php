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
       Schema::create('reminders', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('detail');
        $table->dateTime('deadline'); // The "Schedule"
        $table->boolean('is_dismissed')->default(false); // To hide the popup once seen
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
