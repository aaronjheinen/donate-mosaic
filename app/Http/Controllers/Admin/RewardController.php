<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Set;
use App\Reward;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $set = Set::find(1);
        $rewards = Reward::with('media')->where('set_id', $set->id)->orderby('blocks', 'desc')->get();

        return view('admin.rewards.index', [ 'set' => $set, 'rewards' => $rewards ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.rewards.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        Reward::create(array(
                'set_id' => 1,
                'name' => $request->input('name'),
                'blocks' => $request->input('blocks'),
                'description' => $request->input('description')
            ));

        $set = Set::find(1);
        $rewards = Reward::with('media')->where('set_id', $set->id)->orderby('blocks', 'desc')->get();

        return view('admin.rewards.index', [ 'set' => $set, 'rewards' => $rewards ]);
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
