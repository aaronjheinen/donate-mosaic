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
    public function purchases()
    {
        $purchases = Purchase::with('squares')->get();

        return view('admin.purchases', [ 'purchases' => $purchases ]);
    }
}
