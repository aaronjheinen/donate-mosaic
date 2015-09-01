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
    public function upload(Request $request)
    {
        if($request->hasFile('image')){
            $image = $request->file('image');
            $fileName = time() . '-' . uniqid().'.'. $image->guessClientExtension();
            $request->file('image')->move( public_path() .'/img/uploads', $fileName);
        }

        return Media::create(array(
              'type' => $image->getClientMimeType(),
              'path' => 'img/uploads/'.$fileName,
              'url'  => asset('img/uploads/'.$fileName)
            ));
    }

}
