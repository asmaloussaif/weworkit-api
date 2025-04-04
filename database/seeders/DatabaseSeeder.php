<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* $adminRole = Role::create(['name' => 'admin']);
    $clientRole = Role::create(['name' => 'client']);
    $freelancerRole = Role::create(['name' => 'freelancer']); */

    // Création des permissions
/*     Permission::create(['name' => 'manage users']);
    Permission::create(['name' => 'create project']);
    Permission::create(['name' => 'view projects']);

    // Assignation des permissions aux rôles
    $adminRole->givePermissionTo(['manage users', 'create project', 'view projects']);
    $clientRole->givePermissionTo(['create project', 'view projects']);
    $freelancerRole->givePermissionTo(['view projects']); */

    // Création d'un admin par défaut
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@weworkit.com',
        'password' => bcrypt('password')
    ]);

    $admin->assignRole('admin');
}
  
}
