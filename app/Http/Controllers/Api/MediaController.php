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
            if ($image->isValid())
            {
                $filepath = time() . '-' . uniqid();
                $fileName = $filepath .'.'. $image->guessClientExtension();
                $type = $image->getClientMimeType();
                $dir = public_path() .'/img/uploads/';
                $source = $dir . $fileName;
                $image->move( $dir, $fileName);
                // Create Thumbnail
                // Set a maximum height and width
                $width = 200;
                $height = 200;

                // Get new dimensions
                list($width_orig, $height_orig) = getimagesize($source);

                $ratio_orig = $width_orig/$height_orig;

                if ($width/$height > $ratio_orig) {
                   $width = $height*$ratio_orig;
                } else {
                   $height = $width/$ratio_orig;
                }

                // Resample
                $type = strtolower(substr(strrchr($fileName,"."),1));
                if($type == 'jpeg') $type = 'jpg';
                switch($type){
                  case 'bmp': $image = imagecreatefromwbmp($source); break;
                  case 'gif': $image = imagecreatefromgif($source); break;
                  case 'jpg': $image = imagecreatefromjpeg($source); break;
                  case 'png': $image = imagecreatefrompng($source); break;
                  default : return "Unsupported picture type!";
                }
                $thumbnail = imagecreatetruecolor($width, $height);
                // preserve transparency
                if($type == "gif" or $type == "png"){
                  imagecolortransparent($thumbnail, imagecolorallocatealpha($thumbnail, 0, 0, 0, 127));
                  imagealphablending($thumbnail, false);
                  imagesavealpha($thumbnail, true);
                }
                imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                $thumb_dest = $dir . $filepath .'-thumb.'. $type;
                switch($type){
                  case 'bmp': imagewbmp($thumbnail, $thumb_dest); break;
                  case 'gif': imagegif($thumbnail, $thumb_dest); break;
                  case 'jpg': imagejpeg($thumbnail, $thumb_dest); break;
                  case 'png': imagepng($thumbnail, $thumb_dest); break;
                }

            } else {
              return;
            }

          return Media::create(array(
                'type' => $type,
                'path' => 'img/uploads/'.$fileName,
                'url'  => asset('img/uploads/'.$fileName),
                'thumburl' => $thumb_dest
              ));
        }

    }

}
