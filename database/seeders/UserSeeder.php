<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 3 bad users with no roles
        User::factory()
            ->count(3)
            ->sequence(fn (Sequence $sequence) => ['email' => 'badUser' . ($sequence->index? $sequence->index : "") . '@gmail.com'])
            ->create();

        // 3 admin users
        $adminRole = Role::where('name', 'admin')->first();
        User::factory()
            ->count(3)
            ->sequence(fn (Sequence $sequence) => ['email' => 'admin' . ($sequence->index? $sequence->index : "") . '@weroad.com'])
            ->create(['roleId'=>$adminRole->id]);

        // 3 editor users
        $editorRole = Role::where('name','editor')->first();
        User::factory()
            ->count(3)
            ->sequence(fn (Sequence $sequence) => ['email' => 'editor' . ($sequence->index? $sequence->index : "") . '@weroad.com'])
            ->create(['roleId'=>$editorRole->id]);
    }
}
