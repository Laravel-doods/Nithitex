<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo '--------------------------------'."\n";
        echo '---------Role Seeding----------'."\n";
        $role = new Role;
        $role->name = 'Super Admin';
        $role->save();
        echo "-----------role Seeding name=> $role->name --------" . "\n";
        // echo '-----------------------------------------------------------------' . "\n";
        // echo '-----------Assigning All Permissions to' . $role->name .'--------' . "\n";

        $permissions = Permission::get();
         foreach ($permissions as $key => $value) {
            $role->givePermissionTo($value->name);
            echo '--------Added Permission name =>'. $value->name . '--------' . "\n";
         }
             echo '-------Role Seeding Completed-----------' . "\n";
             echo '-----------------------------------------' . "\n";
    }
}
