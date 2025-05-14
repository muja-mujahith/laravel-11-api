<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function likePost(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'post_id' => 'required|integer',
            
        ]);

        if($validated->fails())
        {
            return response()->json(['error'=>$validated->errors()]);
        }

        try{
            $userLikedPostBefore = Like::where('user_id', auth()->user()->id)
                                        ->where('post_id', $request->post_id)
                                        ->first();
            if($userLikedPostBefore)
            {
                return response()->json(['message'=>'you cannot like a post twise'], 403);
            }                           
            
            $post = new Like();
            $post->post_id = $request->post_id;
            $post->user_id = auth()->user()->id;
            $post->save();
            return response()->json([
                'message' => 'post like added successfully',
            ]);
        }catch(\Exception $th){
            return response()->json($th->getMessage());
        }
    }
}
