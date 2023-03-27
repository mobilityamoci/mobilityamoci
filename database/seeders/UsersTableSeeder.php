<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('users')->delete();

        \DB::table('users')->insert(array (
            0 =>
            array (
                'name' => 'Assistenza',
                'surname' => 'Brainfarm',
                'email' => 'assistenza@brainfarm.eu',
                'email_verified_at' => '2023-03-09 11:18:17',
                'password' => '$2y$10$Md7ugw/HzKhI/qaI/8jLlO6.r0v/rKTIM5iBR.kAVS7aYHmKFCBU.',
                'two_factor_secret' => NULL,
                'two_factor_recovery_codes' => NULL,
                'two_factor_confirmed_at' => NULL,
                'remember_token' => NULL,
                'current_team_id' => NULL,
                'profile_photo_path' => NULL,
                'accepted_at' => '2023-03-08 17:45:24',
                'created_at' => '2023-02-14 09:18:34',
                'updated_at' => '2023-02-28 15:22:11',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'name' => 'Edoardo',
                'surname' => 'Bartolini',
                'email' => 'edoardo.bartolini@brainfarm.eu',
                'email_verified_at' => '2023-03-09 11:18:17',
                'password' => '$2y$10$q49pP0fQxxUAbCSZnZZ8ke4.fs55LiBx9bGr9//PgIfhD684pL0cK',
                'two_factor_secret' => NULL,
                'two_factor_recovery_codes' => NULL,
                'two_factor_confirmed_at' => NULL,
                'remember_token' => NULL,
                'current_team_id' => NULL,
                'profile_photo_path' => NULL,
                'accepted_at' => '2023-03-08 17:45:24',
                'created_at' => '2023-02-22 14:35:48',
                'updated_at' => '2023-02-22 14:35:48',
                'deleted_at' => NULL,
            ),

        ));

    }
}
