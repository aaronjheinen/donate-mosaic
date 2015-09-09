<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use App\Set;
use App\Square;
use App\Purchase;
use App\PurchaseSquare;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ViewController extends Controller
{
    /**
     * Show the login screen to the user
     *
     * @return Response
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Log the user out and then redirect to the login screen
     *
     * @return Response
     */
    public function logout()
    {
      Auth::logout();
      return view('auth.login');
    }

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
