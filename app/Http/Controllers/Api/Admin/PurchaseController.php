<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Set;
use App\Square;
use App\Purchase;
use App\PurchaseSquare;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     * /api/admin/purchases
     * @return Response
     */
    public function index()
    {
        return Purchase::with('squares', 'media')->orderby('created_at', 'desc')->get();
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
        $squares = $request->input('chosen');

        $purchase = Purchase::create(array(
                        'name' => $request->input('name'),
                        'email' => $request->input('email')
                    ));

        foreach( $squares as $square_id ){

            $s = Square::find($square_id);
            $s->class = 'taken';
            $s->status = 'purchased';
            $s->save();

            PurchaseSquare::create(array(
                'purchase_id' => $purchase->id,
                'square_id' => $s->id
            ));

        }

        return PurchaseSquare::where('purchase_id', $purchase->id)->get(); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Set::where('id', $id)->with(['squares' => function($q){
            $q->where('class', 'invisible')->select('id', 'set_id', 'class');
        }])->first();
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
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
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
