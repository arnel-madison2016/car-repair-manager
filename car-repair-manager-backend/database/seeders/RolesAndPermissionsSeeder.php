<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {

        // Permissions générales
        $permissions = [
            //---------- manage users -------
            'create users',
            'view users',
            'edit users',
            'delete users',
            'assign roles',
            'assign permissions',

            //---------- manage profiles -------
            'create profiles',
            'view profiles',
            'edit profiles',
            'delete profiles',

            //---------- manage roles -------
            'create roles',
            'view roles',
            'edit roles',
            'delete roles',

            //---------- manage permissions -------
            'create permissions',
            'view permissions',
            'edit permissions',
            'delete permissions',

            //---------- manage reports -------
            'create reports',
            'view reports',
            'edit reports',
            'delete reports',

            //---------- manage tasks -------
            'create tasks',
            'view tasks',
            'edit tasks',
            'delete tasks',

            //---------- manage repair sheets -------
            'create repair sheets',
            'view repair sheets',
            'edit repair sheets',
            'delete repair sheets',

            //---------- manage appointments -------
            'create appointments',
            'view appointments',
            'edit appointments',
            'delete appointments',

            //---------- manage manufacturers -------
            'create manufacturers',
            'view manufacturers',
            'edit manufacturers',
            'delete manufacturers',

            //---------- manage brands -------
            'create brands',
            'view brands',
            'edit brands',
            'delete brands',

            //---------- manage categories -------
            'create categories',
            'view categories',
            'edit categories',
            'delete categories',

            //---------- manage services -------
            'create services',
            'view services',
            'edit services',
            'delete services',

            //---------- manage pricing -------
            'create prices',
            'view prices',
            'edit prices',
            'delete prices',

            //---------- manage billing -------
            'create bills',
            'view bills',
            'edit bills',
            'delete bills',

            //---------- manage payments -------
            'create payments',
            'view payments',
            'edit payments',
            'delete payments',

            //---------- manage customers -------
            'create customers',
            'view customers',
            'edit customers',
            'delete customers',

            //---------- manage vehicules -------
            'create vehicules',
            'view vehicules',
            'edit vehicules',
            'delete vehicules',

            //---------- manage parameters -------
            'create parameters',
            'view parameters',
            'edit parameters',
            'delete parameters',

            //---------- others -------
            'create dashboard',
            'view dashboard',
            'edit dashboard',
            'delete dashboard',
        ];

        // creation des permissions
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Creation des Rôles et assignation des Permissions
        // l'admin aura toutes les permissions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->givePermissionTo([         

            //---------- manage profiles -------
            'create profiles',
            'view profiles',
            'edit profiles',

            //---------- manage reports -------
            'create reports',
            'view reports',
            'edit reports',

            //---------- manage tasks -------
            'create tasks',
            'view tasks',
            'edit tasks',
            'delete tasks',

            //---------- manage repair sheets -------
            'create repair sheets',
            'view repair sheets',
            'edit repair sheets',

            //---------- manage appointments -------
            'view appointments',
            'edit appointments',

            //---------- manage manufacturers -------
            'create manufacturers',            
            'view manufacturers',
            'edit manufacturers',

            //---------- manage brands -------
            'create brands',
            'view brands',
            'edit brands',

            //---------- manage categories -------
            'create categories',
            'view categories',
            'edit categories',

            //---------- manage services -------
            'create services',
            'view services',
            'edit services',

            //---------- manage pricing -------
            'create prices',
            'view prices',
            'edit prices',

            //---------- manage billing -------
            'view bills',

            //---------- manage payments -------
            'view payments',

            //---------- manage customers -------
            'view customers',

            //---------- manage vehicules -------
            'create vehicules',
            'view vehicules',
            'edit vehicules',

            //---------- others -------
            'view dashboard',
        ]);

        $mechanic = Role::firstOrCreate(['name' => 'mechanic']);
        $mechanic->givePermissionTo([
            //---------- manage profiles -------
            'create profiles',
            'view profiles',
            'edit profiles',

            //---------- manage tasks -------
            'view tasks',
            'edit tasks',

            //---------- manage repair sheets -------
            'view repair sheets',

            //---------- manage manufacturers -------
            'view manufacturers',

            //---------- manage brands -------
            'view brands',

            //---------- others -------
            'view dashboard',
        ]);

        $accountant = Role::firstOrCreate(['name' => 'accountant']);
        $accountant->givePermissionTo([
            //---------- manage profiles -------
            'create profiles',
            'view profiles',
            'edit profiles',

            //---------- manage reports -------
            'create reports',
            'view reports',
            'edit reports',

            //---------- manage pricing -------
            'create prices',
            'view prices',
            'edit prices',

            //---------- manage billing -------
            'create bills',
            'view bills',
            'edit bills',

            //---------- manage payments -------
            'create bills',
            'view bills',
            'edit bills',

            //---------- manage customers -------
            'view customers',

            //---------- others -------
            'view dashboard',
        ]);

        $customer = Role::firstOrCreate(['name' => 'customer']);
        $customer->givePermissionTo([
            //---------- manage profiles -------
            'create profiles',
            'view profiles',
            'edit profiles',

            //---------- manage repair sheets -------
            'view repair sheets',

            //---------- manage appointments -------
            'create appointments',
            'view appointments',
            'edit appointments',

            //---------- manage categories -------
            'view categories',

            //---------- manage services -------
            'view services',

            //---------- manage pricing -------
            'view prices',            

            //---------- manage billing -------
            'view bills',

            //---------- manage manufacturers -------
            'view manufacturers',

            //---------- manage brands -------
            'view brands',

            //---------- manage vehicules -------
            'create vehicules',
            'view vehicules',
            'edit vehicules',

            //---------- others -------
            'view dashboard',
        ]);

        $guest = Role::firstOrCreate(['name' => 'guest']);
        $guest->givePermissionTo([
            'view services', 
            'view appointments',
        ]);
    }
}
