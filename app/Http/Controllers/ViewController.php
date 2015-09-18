<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Set;
use App\Square;
use App\Purchase;
use App\PurchaseSquare;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ViewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function thanks($purchase_id)
    {
        $purchase = Purchase::find($purchase_id);
        $set = Set::with('squares.purchase.media')->where('id' , 1)->first();
        
        return view('public.thanks', [ 'set' => $set, 'purchase' => $purchase ]);
    }
}
