<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Set;
use App\Square;
use App\Purchase;
use App\PurchaseSquare;
use Jenssegers\Agent\Agent;

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
        $agent = new Agent();
        $set = Set::with('media', 'squares.purchase.media', 'rewards')->where('id' , 1)->first();

        if( $agent->isMobile() || $agent->isTablet() ){
            return view('public.mobile.index', [ 'set' => $set ]); 
        }

        return view('public.index', [ 'set' => $set ]);
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
     * Make sure all selected blocks are still available
     * If not, return array of unavailable blocks
     *
     * @param  Request  $request
     * @return Response
     */
    public function available(Request $request)
    {
        $squares = $request->input('chosen');
        $unavailable = [];
        foreach( $squares as $square_id ){

            $s = Square::find($square_id);
            if($s->status != 'available'){
                $unavailable[] = $s->id;
            }
        }
        if(count($unavailable) > 0){
            return $unavailable;
        } 
        
        return array( 'status' => 'success' );
        
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

        // Create a Customer
        $customer = \Stripe\Customer::create(array(
          "source" => $request->input('token_id'),
          "description" => $request->input('name') . ' ' . $request->input('email')
        ));

        // Charge the Customer instead of the card
        \Stripe\Charge::create(array(
          "amount" => $request->input('price') * 100, # amount in cents
          "currency" => "usd",
          "customer" => $customer->id)
        );

        $set = Set::find(1);

        $data = array(
            'set_id' => $set->id,
            'customer_id'  => $customer->id,
            'price' => $request->input('price'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'optin' => $request->input('optin')
        );

        if( $request->has('media_id') ){
            $data['media_id'] = $request->input('media_id');
        } else if( $request->has('color') ){
            $data['color'] = $request->input('color');
        }
        $purchase = Purchase::create( $data );
        
        // Mobile doesn't have chosen array
        if( $request->has('mobile') ){

            $squares = Square::where('status' , 'available')->where('set_id', $set->id)->take( $request->input('blocks') )->get();

            foreach( $squares as $s ){

                $s->class = 'taken';
                $s->status = 'unavailable';
                $s->save();

                PurchaseSquare::create(array(
                    'purchase_id' => $purchase->id,
                    'square_id' => $s->id
                ));

            }

        } else {

            $squares = $request->input('chosen');

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

        }

        
        return $purchase;
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
