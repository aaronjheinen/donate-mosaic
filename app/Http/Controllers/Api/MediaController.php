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
     * Upload an image and generate a thumbnail with the same aspect ratio
     *
     * @return Response
     */
    public function upload(Request $request)
    {
        if($request->hasFile('image')){
            $image = $request->file('image');
            if ($image->isValid())
            {
                $type = $image->guessClientExtension();
                if($type == 'jpeg') $type = 'jpg';
                $filepath = time() . '-' . uniqid();
                $filename = $filepath .'.'. $type;
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
                // Image invalid
                return;
            }

            return Media::create(array(
                'type' => $type,
                'path' => 'img/uploads/'.$filename,
                'thumb_path' => 'img/uploads/'.$thumbname,
                'url'  => asset('img/uploads/'.$filename),
                'thumburl' =>  asset('img/uploads/'.$thumbname)
            ));
        }

    }
    /**
     * Upload the generated static image
     *
     * @return Response
     */
    public function generate(Request $request)
    {
        if($request->has('image')){
          $set_id = 1;
          $base64 = substr($request->input('image'), strpos($request->input('image'), ",")+1);
          $filename = round(microtime(true) * 1000).'.jpg';
          $path = 'img/sets/'. $set_id .'/';
          $filelocation = public_path('/') . $path . $filename;
          file_put_contents($filelocation, base64_decode($base64));

          $media = Media::create(array(
                'type' => 'jpg',
                'path' => $path . $filename,
                'url'  => asset($path . $filename)
              ));

          $set = Set::find($set_id);
          $set->media_id = $media->id;
          $set->save();

          return $media;
        }
    }

}
