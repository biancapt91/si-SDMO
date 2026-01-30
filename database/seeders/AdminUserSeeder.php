<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nip = env('ADMIN_NIP', '0001');
        $password = env('ADMIN_PASSWORD', 'secret');

        $user = User::firstOrNew(['nip' => $nip]);
        $user->name = 'Administrator';
        $user->password = Hash::make($password);
        $user->role = 'admin';
        $user->save();

        $this->command->info("Admin user seeded: NIP {$nip}");
    }
}
