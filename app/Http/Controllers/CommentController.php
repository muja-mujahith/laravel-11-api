<?php

namespace App\Http\Controllers;

// use Dom\Comment;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function postComment(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'post_id' => 'required|integer',
            'comment' => 'required|string'
        ]);

        if($validated->fails())
        {
            return response()->json(['error'=>$validated->errors()]);
        }

        try{
            $post = new Comment();
            $post->post_id = $request->post_id;
            $post->comment = $request->comment;
            $post->user_id = auth()->user()->id;
            $post->save();
            return response()->json([
                'message' => 'comment added successfully',
            ]);
        }catch(\Exception $th){
            return response()->json($th->getMessage());
        }
    }
}
