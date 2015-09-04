<?php

namespace App\Http\Controllers\Admin;

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
        $purchases = Purchase::with('squares', 'media')->orderby('created_at', 'desc')->get();

        return view('admin.purchases', [ 'purchases' => $purchases ]);
    }
}
