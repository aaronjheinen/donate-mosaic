<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Media;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function upload()
    {
        $hasfile = 'false';
        if($request->hasFile('image')){
            $image = $request->file('image');
        }

        return Media::create(array(
                'type' => 'jpg',
                'path' => 'here',
                'url' => 'here.jpg'
            ));
    }

}
