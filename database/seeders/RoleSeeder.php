<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Psy\Readline\Hoa\Console;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(__DIR__.'/roles.json');
        $roles = json_decode($json);
        foreach ($roles as $role_) {
            $role = new Role();
            $role->id = $role_->id;
            $role->name = $role_->name;
            $role->save();
        }
    }
}
