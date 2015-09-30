<?php

use Illuminate\Database\Seeder;
use App\Set;
use App\Square;

class SquaresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $set = Set::create(array(
            'name' => 'StartingBlock Madison',
            'rows' => 75,
            'cols' => 100,
            'price' => 25,
            'available' => 75 * 100
            ));
        for( $x = 1; $x <= 100; $x++ ){
        	for( $y = 1; $y <= 75; $y++ ){
        		 DB::table('squares')->insert([
                    'set_id' => $set->id,
                    'number' => x * y,
                    'status' => 'available'
		        ]);
        	}
        }
    }
}
