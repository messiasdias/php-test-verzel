<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(0, 10) as $id) {
            $idText = $id > 0 ? $id : "";

            if (env("DEFAULT_ADMIN_NAME{$idText}") && env("DEFAULT_ADMIN_EMAIL{$idText}")) {
                $user = User::create([
                    "name" => ucwords(trim(env("DEFAULT_ADMIN_NAME{$idText}"))),
                    "email" => trim(env("DEFAULT_ADMIN_EMAIL{$idText}")),
                    "password" => Hash::make(env("DEFAULT_ADMIN_PASS", "P@55w0rd123")),
                    "permissions" => json_encode([
                        "users" => true,
                        "vehicles" => true
                    ])
                ]);

                $user->save();
            }
        }
    }
}
