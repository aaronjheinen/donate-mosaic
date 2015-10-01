<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Set;
use App\Square;
use App\Purchase;
use App\PurchaseSquare;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $set = Set::with('squares.purchase')->where('id' , 1)->first();

        return view('admin.set', [ 'set' => $set ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     * /api/admin/set/{id}
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Set::where('id', $id)->with(['squares' => function($q){
            $q->where('status', 'invisible')->select('id', 'set_id', 'class', 'status');
        }])->first();
    }

    /**
     * Display the specified resource.
     * /api/admin/set/{id}/squares
     * @param  int  $id
     * @return Response
     */
    public function squares($id)
    {
        return Set::where('id', $id)->with('squares')->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Resizing the grid
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $set = Set::find($id);
        if( $request->input('rows') != $set->rows || $request->input('cols') != $set->cols ){
            $total = $request->input('rows') * $request->input('cols');
            $diff = $total - ( $set->rows * $set->cols );
            if( $diff < 0 ){
                // New Size is less than Old Size - move purchases
                $purchasedSquaresToMove = [];
                $squaresWithPurchase = Square::where('number', '>', $total)->where('set_id', $set->id)->where('class', 'taken')->with('purchase')->get();
                foreach($squaresWithPurchase as $squareWithPurchase){
                    $cnt = Square::where('set_id', $set->id)->where('status', 'available')->count();
                    if ($cnt == 0)
                        return;

                    $randIndex = rand(0, $cnt-1);
                    $square = Square::where('set_id', $set->id)->where('status', 'available')->skip($randIndex)->take(1)->first();
                    $square->class = 'taken';
                    $square->status = 'unavailable';
                    $square->save();
                    $newPurchaseSquare = PurchaseSquare::where('square_id', $squareWithPurchase->purchase[0]->pivot->square_id)->first();
                    $newPurchaseSquare->square_id = $square->id;
                    $newPurchaseSquare->save();

                }
                $squaresToDelete = Square::where('number', '>', $total)->where('set_id', $set->id)->delete();
            } else {
                // New size is greater - create new squares
                $number = Square::where('set_id', $set->id)->count();
                for($i=1; $i <= abs($diff); $i++){
                    Square::create(array(                            
                        'set_id' => $set->id,
                        'number' => $number + $i,
                        'status' => 'available'
                    ));
                }
            }
        }
        // Reset invisible because if you're resizing it the invisible structure is going to be off
        $squaresToSet = array(
            'class' => null,
            'status' => 'available'
        );
        $squaresWithInvisible = Square::where('set_id', $set->id)->where('status', 'invisible')->update($squaresToSet);
        $set->name = $request->input('name');
        $set->price = $request->input('price');
        $set->rows = $request->input('rows');
        $set->cols = $request->input('cols');
        $set->save();

        return $set; 
    }
    /**
     * Update the specified resource in storage.
     * /api/admin/set/{id}/move
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function move(Request $request, $id)
    {
        $from = PurchaseSquare::where('square_id', $request->input('from'))->first();
        $from->square_id = $request->input('to');
        $from->save();

        $square_old = Square::find($request->input('from'));
        $square_old->status = 'available';
        $square_old->class = null;
        $square_old->save();
        
        $square_new = Square::find($request->input('to'));
        $square_new->class = 'taken';
        $square_new->status = 'unavailable';
        $square_new->save();

        return $from; 
    }

     /**
     * Update the specified resource in storage.
     * /api/admin/set/{id}/available
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function available(Request $request, $id)
    {
        $unavailable = $request->input('chosen');

        $available = $request->input('unchosen');

        if(count($available) > 0 ){

            foreach( $available as $square_id ){

                $s = Square::find($square_id);
                $s->status = 'available';
                $s->save();
            }

        }
        if(count($unavailable) > 0 ){

            foreach( $unavailable as $square_id ){

                $s = Square::find($square_id);
                $s->status = 'invisible';
                $s->save();

            }
        }


        $set = Set::find($id);
        $set->available = $set->rows * $set->cols - Square::where('set_id', $id)->where('status', 'invisible')->count();
        $set->save();

        return $set; 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
