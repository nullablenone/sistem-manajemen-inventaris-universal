<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Membuat Role
        // Spatie akan otomatis menyimpan role ini ke tabel 'roles'
        $roleAdministrator = Role::create(['name' => 'administrator']);
        $roleAdmin = Role::create(['name' => 'admin']);

        // 2. Membuat User "administrator"
        $administrator = User::create([
            'name'     => 'administrator',
            'email'    => 'administrator@xxx.com',
            'password' => Hash::make('administrator@xxx.com'),
        ]);

        // Memasang role administrator ke user tersebut
        $administrator->assignRole($roleAdministrator);

        // 3. Membuat User "admin"
        $admin = User::create([
            'name'     => 'admin',
            'email'    => 'admin@xxx.com',
            'password' => Hash::make('admin@xxx.com')
        ]);

        // Memasang role admin ke user tersebut
        $admin->assignRole($roleAdmin);
    }
}
