<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['ADMIN', 'WALI_KELAS'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }
        $admin = User::updateOrCreate(
            ['email' => 'admin@smkn1wonosari.sch.id'],
            [
                'id'                => (string) \Illuminate\Support\Str::uuid(),
                'name'              => 'Administrator',
                'password'          => \Illuminate\Support\Facades\Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('ADMIN');
        $this->command->info('Roles dan User Admin berhasil disiapkan!');
    }
}
