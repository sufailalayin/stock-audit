<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('audit_files', function (Blueprint $table) {
        $table->string('status')->default('pending');
        $table->timestamp('completed_at')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_files', function (Blueprint $table) {
            //
        });
    }
};
