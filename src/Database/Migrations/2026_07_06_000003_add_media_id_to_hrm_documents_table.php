<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hrm_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('hrm_documents', 'media_id')) {
                $table->foreignId('media_id')->nullable()->after('document')
                    ->constrained('media')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('hrm_documents', function (Blueprint $table) {
            if (Schema::hasColumn('hrm_documents', 'media_id')) {
                $table->dropConstrainedForeignId('media_id');
            }
        });
    }
};
