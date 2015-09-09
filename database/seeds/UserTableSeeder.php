<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('users')->insert([
          'name' => 'StartingBlock Madison',
          'email' => getenv('APP_SUPERUSER'),
          'password' => bcrypt(getenv('APP_SUPERPASS')),
      ]);
    }
}
