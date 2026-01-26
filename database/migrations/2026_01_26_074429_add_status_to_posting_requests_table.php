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
        Schema::table('posting_requests', function (Blueprint $table) {
        // Options: pending, posted, archived
        $table->string('status')->default('pending')->after('ctrl_no');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posting_requests', function (Blueprint $table) {
            //
        });
    }
};
