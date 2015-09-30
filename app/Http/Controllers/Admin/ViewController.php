<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use App\Set;
use App\SetContent;
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
        $set = Set::with('purchases.squares', 'squares.purchase.media')->where('id' , 1)->first();
        $purchased_squares = 0;
        foreach( $set->purchases as $purchase ){
            $purchased_squares = $purchased_squares + count( $purchase->squares );
        }
        $squares = Square::where('class', 'taken')->where('set_id', $set->id)->get();
        
        return view('admin.index', [ 'set' => $set, 'squares' => $squares, 'purchased_squares' => $purchased_squares ]);
    }

    /**
     * Display the grid to update
     * /admin/set/settings
     * @return Response
     */
    public function settings()
    {
        $set = Set::with('squares')->where('id' , 1)->first();

        return view('admin.set.settings', [ 'set' => $set ]);
    }

    /**
     * Display the grid to update
     * /admin/set/available
     * @return Response
     */
    public function set()
    {
        $set = Set::with('squares.purchase')->where('id' , 1)->first();

        return view('admin.set.index', [ 'set' => $set ]);
    }

    /**
     * Display the grid to generate a large image from
     * /admin/set/image
     * @return Response
     */
    public function image()
    {
        $set = Set::with('squares.purchase.media')->where('id' , 1)->first();

        return view('admin.set.image', [ 'set' => $set ]);
    }

    /**
     * Display the grid with purchases so the admin can move them
     * /admin/set/purchases
     * @return Response
     */
    public function purchases()
    {
        $set = Set::with('purchases.squares', 'squares.purchase.media')->where('id' , 1)->first();
        
        return view('admin.set.purchases', [ 'set' => $set ]);
    }

    /**
     * Display the grid to generate a large image from
     * /admin/set/content
     * @return Response
     */
    public function content()
    {
        $set = Set::with('squares.purchase.media', 'content', 'rewards')->where('id' , 1)->first();
        
        return view('admin.set.content', [ 'set' => $set]);
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
