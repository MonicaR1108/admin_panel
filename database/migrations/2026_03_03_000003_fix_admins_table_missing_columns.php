<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('admins')) {
            return;
        }

        Schema::table('admins', function (Blueprint $table) {
            if (! Schema::hasColumn('admins', 'full_name')) {
                $table->string('full_name', 190)->nullable()->after('id');
            }

            if (! Schema::hasColumn('admins', 'username')) {
                $table->string('username', 100)->nullable()->after('full_name');
            }

            if (! Schema::hasColumn('admins', 'email')) {
                $table->string('email', 190)->nullable()->after('username');
            }

            if (! Schema::hasColumn('admins', 'password')) {
                $table->string('password')->nullable()->after('email');
            }

            if (! Schema::hasColumn('admins', 'created_at')) {
                $table->timestamp('created_at')->nullable()->useCurrent()->after('password');
            }
        });

        // Best-effort backfill for existing rows.
        if (Schema::hasColumn('admins', 'full_name')) {
            $fullNameExpr = Schema::hasColumn('admins', 'name')
                ? "COALESCE(NULLIF(`name`,''), NULLIF(`username`,''), 'Admin')"
                : "COALESCE(NULLIF(`username`,''), 'Admin')";

            DB::table('admins')
                ->whereNull('full_name')
                ->update(['full_name' => DB::raw($fullNameExpr)]);
        }

        if (Schema::hasColumn('admins', 'username')) {
            DB::table('admins')
                ->whereNull('username')
                ->update(['username' => DB::raw("COALESCE(NULLIF(email,''), CONCAT('admin', id))")]);
        }

        // Best-effort unique constraints (ignore if already exist / fail).
        try {
            DB::statement('ALTER TABLE `admins` ADD UNIQUE KEY `admins_username_unique` (`username`)');
        } catch (Throwable) {
        }
        try {
            DB::statement('ALTER TABLE `admins` ADD UNIQUE KEY `admins_email_unique` (`email`)');
        } catch (Throwable) {
        }
    }

    public function down(): void
    {
        // Non-destructive rollback intentionally omitted.
    }
};
