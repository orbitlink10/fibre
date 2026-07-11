<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->text('qualification_summary')->nullable()->after('document_path');
            $table->text('specialties')->nullable()->after('qualification_summary');
            $table->unsignedSmallInteger('experience_years')->nullable()->after('specialties');
        });
    }

    public function down(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn(['qualification_summary', 'specialties', 'experience_years']);
        });
    }
};
