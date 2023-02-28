<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'all_schools',
            'school',
//            'all_sections',
            'section',
            'base',
            'admin'
        ];


        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission
            ]);
        }

        $admin = Role::create(['name' => 'Admin']);

        $admin_permissions = [
            'admin',
            'all_schools'
        ];

        foreach ($admin_permissions as $perm) {
            $admin->givePermissionTo($perm);
        }

        $MMProvinciale = Role::create(['name' => 'MMProvinciale']);

        $MMProvinciale_permissions = [
            'all_schools'
        ];

        foreach ($MMProvinciale_permissions as $perm) {
            $MMProvinciale->givePermissionTo($perm);
        }

        $MMScolastico = Role::create(['name' => 'MMScolastico']);

        $MMScolastico_permissions = [
            'school'
        ];

        foreach ($MMScolastico_permissions as $perm) {
            $MMScolastico->givePermissionTo($perm);
        }

        $insegnante = Role::create(['name' => 'Insegnante']);

        $insegnante_permissions = [
            'section'
        ];

        foreach ($insegnante_permissions as $perm) {
            $insegnante->givePermissionTo($perm);
        }

        $utenteBase = Role::create(['name' => 'Utente Base']);

        $utenteBase_permissions = [
            'base'
        ];

        foreach ($utenteBase_permissions as $perm) {
            $utenteBase->givePermissionTo($perm);
        }
    }
}
