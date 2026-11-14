<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);

        if (! User::query()->where('email', 'admin@bazardev.com')->exists()) {
            $password = Str::password(20);

            $admin = User::query()->create([
                'name' => 'Super Admin',
                'email' => 'admin@bazardev.com',
                'password' => $password,
                'is_active' => true,
            ]);

            $admin->assignRole('Super Admin');

            file_put_contents(
                storage_path('app/seeded-admin-credentials.txt'),
                "email: admin@bazardev.com\npassword: {$password}\n(delete this file after first login)\n",
            );

            $this->command?->info('Super Admin created — credentials written to storage/app/seeded-admin-credentials.txt');
        }
    }
}
