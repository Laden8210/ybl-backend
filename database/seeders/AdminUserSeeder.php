<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = env('ADMIN_NAME', 'Administrator');
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD', 'password');
        $reset = filter_var(env('ADMIN_RESET', false), FILTER_VALIDATE_BOOLEAN);

        $user = User::firstOrNew(['email' => $email]);

        $user->name = $name;
        $user->role = 'admin';
        $user->is_active = true;

        if (!$user->exists || $reset) {
            $user->password = $password;
        }

        $user->save();
    }
}
