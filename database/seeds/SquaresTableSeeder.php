<?php

use Illuminate\Database\Seeder;

class SquaresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for( $x = 0; $x < 100; $x++ ){
        	for( $y = 0; $y < 100; $y++ ){
        		 DB::table('squares')->insert([
		            'x' => $x,
		            'y' => $y
		        ]);
        	}
        }
    }
}
