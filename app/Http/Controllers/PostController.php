<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Notifications\SignupActivate;
use Illuminate\Support\Str;
use Laravolt\Avatar\Facade as Avatar;
use Storage;
use App\Posts;
use Image;
use Validator,Redirect,Response,File;
class PostController extends Controller
{
  
    /**
     * Upload a post for user
     *
     * @return [json] user object
     */
    public function uploadPosts(Request $request)
    {
        $this->validate($request, [
            'file' => 'required',
            'caption' => 'required|string',
            'hashtags' => 'required|string',
        ]);
        if($request->get('file'))
       {
          $image = $request->get('file');
          $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
          \Image::make($request->get('file'))->save(public_path('images/').$name);
        }

        $user = $request->user();
        $post = new Posts();
        $post->user_id = $user['id'];
        $post->username = $user['username'];
        $post->caption = $request['caption'];
        $post->hashtags =  json_encode(explode (",", $request['hashtags']));
        $post->filename= $name;
        $post->save();
        return response()->json([
            'success' => true,
            'id' => $user->id,
            'name' => $user->first_name,
            'email' => $user->email,
        ], 201);
    }


    public function getTimeline(Request $request){
        $user = $request->user();
        return response()->json([
            'success' => true,
            'id' => $user->id,
            'name' => $user->first_name,
            'email' => $user->email,
        ], 201);
    }

}
