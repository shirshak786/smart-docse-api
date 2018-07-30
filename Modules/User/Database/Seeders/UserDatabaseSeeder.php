<?php

namespace Modules\User\Database\Seeders;

use Modules\Role\Models\Role;
use Modules\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Default password
        $defaultPassword = app()->environment('production') ? str_random() : 'admin123';
        $this->command->getOutput()->writeln("<info>Default password:</info> $defaultPassword");

        // Create super admin user
        $user = new User();
        $role = new Role();

        $user->create([
            'name'      => 'Super admin',
            'email'     => 'superadmin@admin.com',
            'password'  => bcrypt($defaultPassword),
            'active'    => true,
            'confirmed' => true,
            'locale'    => app()->getLocale(),
            'timezone'  => config('app.timezone'),
        ]);

        /*
         * Create roles
         */
        $administratorRole = $role->create([
            'name'         => 'administrator',
            'display_name' => [
                'en' => 'Administrator',
                'fr' => 'Administrateur',
                'es' => 'Administrador',
                'ar' => 'مدير',
            ],
            'description' => [
                'en' => 'Access to mostly web features',
                'fr' => 'Accès à la plupart des fonctionnalités du site',
                'es' => 'Acceso a la mayoría de las características web',
                'ar' => 'قادر على الوصول إلى أغلب ميزات الموقع',
            ],
            'order' => 0,
        ]);

        foreach (
            [
                'access backend',
                'view posts',
                'create posts',
                'edit posts',
                'delete posts',
                'publish posts',
                'view form_settings',
                'create form_settings',
                'edit form_settings',
                'delete form_settings',
                'view form_submissions',
                'delete form_submissions',
                'view users',
                'create users',
                'edit users',
                'delete users',
                'impersonate users',
                'view roles',
                'create roles',
                'edit roles',
                'delete roles',
                'view metas',
                'create metas',
                'edit metas',
                'delete metas',
                'view redirections',
                'create redirections',
                'edit redirections',
                'delete redirections',
            ] as $name) {
            $administratorRole->permissions()->create(['name' => $name]);
        }

        $administrator = $user->create([
            'name'      => 'Administrator',
            'email'     => 'admin@example.com',
            'password'  => bcrypt($defaultPassword),
            'active'    => true,
            'confirmed' => true,
            'locale'    => app()->getLocale(),
            'timezone'  => config('app.timezone'),
        ]);

        $administrator->roles()->save($administratorRole);
    }
}
