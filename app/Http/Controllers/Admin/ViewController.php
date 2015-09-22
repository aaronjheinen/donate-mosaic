<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use App\Set;
use App\Square;
use App\Purchase;
use App\PurchaseSquare;
use App\Reward;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ViewController extends Controller
{
    /**
     * Display an overview of the donations and progress
     * /admin
     * @return Response
     */
    public function index()
    {
        $set = Set::with('purchases', 'squares.purchase')->where('id' , 1)->first();
        $squares = Square::where('class', 'taken')->where('set_id', $set->id)->get();
        
        return view('admin.index', [ 'set' => $set, 'squares' => $squares ]);
    }

    /**
     * Display the grid to update
     * /admin/set
     * @return Response
     */
    public function set()
    {
        $set = Set::with('squares.purchase')->where('id' , 1)->first();

        return view('admin.set', [ 'set' => $set ]);
    }

    /**
     * Display the grid to generate a large image from
     * /admin/set/image
     * @return Response
     */
    public function image()
    {
        $set = Set::with('squares.purchase')->where('id' , 1)->first();

        return view('admin.image', [ 'set' => $set ]);
    }

    /**
     * Show the login screen to the user
     *
     * @return Response
     */
    public function login()
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
}
