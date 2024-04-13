<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

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
        User::factory()
            ->count(3)
            ->sequence(fn (Sequence $sequence) => ['email' => 'admin' . ($sequence->index? $sequence->index : "") . '@weroad.com'])
            ->afterCreating(function (User $user) {
                $role = Role::where('name', 'admin')->first();
                $user->roleId = $role->id;
            })
            ->create();

        // 3 editor users
        User::factory()
            ->count(3)
            ->sequence(fn (Sequence $sequence) => ['email' => 'editor' . ($sequence->index? $sequence->index : "") . '@weroad.com'])
            ->afterCreating(function (User $user) {
                $role = Role::where('name', 'editor')->first();
                $user->roleId = $role->id;
            })
            ->create();
    }
}
