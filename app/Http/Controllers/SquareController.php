<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Set;
use App\Square;
use App\Purchase;
use App\PurchaseSquare;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SquareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $set = Set::with('squares.purchase.media', 'rewards')->where('id' , 1)->first();

        return view('public', [ 'set' => $set ]);
    }

    public function admin()
    {
        $set = Set::with('squares.purchase')->where('id' , 1)->first();

        return view('admin.index', [ 'set' => $set ]);
    }

    public function fullscreen()
    {
        $set = Set::with('squares.purchase')->where('id' , 1)->first();

        return view('admin.fullscreen', [ 'set' => $set ]);
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
        \Stripe\Stripe::setApiKey(env('STRIPE_PRI', null));

        $squares = $request->input('chosen');

        // Create a Customer
        $customer = \Stripe\Customer::create(array(
          "source" => $request->input('token_id'),
          "description" => $request->input('name') . ' ' . $request->input('email'))
        );

        // Charge the Customer instead of the card
        \Stripe\Charge::create(array(
          "amount" => $request->input('price') * 100, # amount in cents
          "currency" => "usd",
          "customer" => $customer->id)
        );

        $set = Set::find(1);

        $data = array(
            'customer_id'  => $customer->id,
            'price' => $request->input('price'),
            'name' => $request->input('name'),
            'email' => $request->input('email')
        );

        if( $request->has('media_id') ){
            $data['media_id'] = $request->input('media_id');
        } else if( $request->has('color') ){
            $data['color'] = $request->input('color');
        }
        $purchase = Purchase::create( $data );

        foreach( $squares as $square_id ){

            $s = Square::find($square_id);
            $s->class = 'taken';
            $s->status = 'unavailable';
            $s->save();

            PurchaseSquare::create(array(
                'purchase_id' => $purchase->id,
                'square_id' => $s->id
            ));

        }

        return PurchaseSquare::where('purchase_id', $purchase->id)->get(); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function adminUpdate(Request $request)
    {
        $id =  $request->input('id');

        $unavailable = $request->input('chosen');

        $reset_squares = Square::where('set_id', $id)->where('status', 'invisible')->get();

        if(count($reset_squares) > 0 ){

            foreach( $reset_squares as $reset_square ){

                $reset_square->status = 'available';
                $reset_square->save();
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
        $set->name = $request->input('name');
        $set->price = $request->input('price');
        $set->available = $set->rows * $set->cols - count( $unavailable );
        $set->save();

        return $set; 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
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
