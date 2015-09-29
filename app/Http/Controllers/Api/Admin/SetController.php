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
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
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
        $set->name = $request->input('name');
        $set->price = $request->input('price');
        $set->available = $set->rows * $set->cols - Square::where('set_id', $id)->where('status', 'invisible')->count();
        $set->save();

        return $set; 
    }

    /**
     * Resize the grid and re-assign purchases out of grid into random.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function resize(Request $request, $id)
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
        $set->name = $request->input('name');
        $set->price = $request->input('price');
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
