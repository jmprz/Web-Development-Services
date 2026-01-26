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
    Schema::create('posting_requests', function (Blueprint $table) {
        $table->id();
        $table->string('department');
        $table->text('particulars');
        $table->string('personnel');
        $table->date('date_to_be_posted');
        $table->string('attachment_link');
        $table->string('doc_title');
        $table->string('doc_no');
        $table->string('ctrl_no')->unique(); // Unique for tracking
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posting_requests');
    }
};
