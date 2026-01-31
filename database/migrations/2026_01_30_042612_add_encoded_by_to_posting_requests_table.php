<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posting_requests', function (Blueprint $table) {
            // Adding the column after 'status'
            $table->string('encoded_by')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('posting_requests', function (Blueprint $table) {
            $table->dropColumn('encoded_by');
        });
    }
};