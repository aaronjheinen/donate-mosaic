<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Media;
use App\Set;

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
                $filename = $filepath .'.'. $image->guessClientExtension();
                $type = $image->getClientMimeType();
                $dir = public_path() .'/img/uploads/';
                $source = $dir . $filename;
                $image->move( $dir, $filename);
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
                $type = strtolower(substr(strrchr($filename,"."),1));
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
                $thumbname = $filepath .'-thumb.'. $type;
                $thumb_dest = $dir . $thumbname;
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
                'path' => 'img/uploads/'.$filename,
                'url'  => asset('img/uploads/'.$filename),
                'thumburl' =>  asset('img/uploads/'.$thumbname)
              ));
        } else if($request->has('image')){
          $base64 = substr($request->input('image'), strpos($request->input('image'), ",")+1);
          $filename = 'floorplan-mobile.jpg';
          $dir = public_path() .'/img/';
          $filelocation = $dir . $filename;
          file_put_contents($filelocation, base64_decode($base64));

          $media = Media::create(array(
                'type' => 'jpg',
                'path' => 'img/'.$filename,
                'url'  => asset('img/'.$filename)
              ));

          $set = Set::find(1);
          $set->media_id = $media->id;
          $set->save();

          return $media;
        }

    }

}
