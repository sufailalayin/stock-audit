<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('audit_files', function (Blueprint $table) {
            if (!Schema::hasColumn('audit_files', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('audit_files', function (Blueprint $table) {
            if (Schema::hasColumn('audit_files', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
        });
    }
};