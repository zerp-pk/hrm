<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('employee_documents', 'media_id')) {
                $table->foreignId('media_id')->nullable()->after('file_path')
                    ->constrained('media')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('employee_documents', function (Blueprint $table) {
            if (Schema::hasColumn('employee_documents', 'media_id')) {
                $table->dropConstrainedForeignId('media_id');
            }
        });
    }
};
