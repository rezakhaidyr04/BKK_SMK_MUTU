<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;

class TempCompanySeeder extends Seeder
{
    public function run()
    {
        $email = 'temp-company+1@example.com';

        if (User::where('email', $email)->exists()) {
            $this->command->info('Temp user already exists: ' . $email);
            return;
        }

        $user = User::create([
            'name' => 'Temp Company',
            'email' => $email,
            'password' => bcrypt('Password123!'),
            'role' => 'company',
        ]);

        Company::create([
            'user_id' => $user->id,
            'name' => 'Temp Company Inc',
            'verification_status' => 'verified',
        ]);

        $this->command->info('CREATED:' . $email . ':Password123!');
    }
}
