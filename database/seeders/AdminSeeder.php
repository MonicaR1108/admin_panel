<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'full_name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('Admin@12345'),
        ];

        // Some legacy schemas may contain a non-null `name` column.
        if (Schema::hasColumn('admins', 'name')) {
            $defaults['name'] = 'Admin';
        }

        // Some legacy schemas might not have `full_name`.
        if (! Schema::hasColumn('admins', 'full_name')) {
            unset($defaults['full_name']);
        }

        Admin::query()->firstOrCreate(
            ['username' => 'admin'],
            $defaults,
        );
    }
}
