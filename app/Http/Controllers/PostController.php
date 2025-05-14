<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;


class PostController extends Controller
{
    public function addNewPost(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'title' => 'required|string',
            'content' => 'required|string',
            // 'user_id' => 'required|integer',
        ]);

        if($validated->fails())
        {
            return response()->json($validated->errors());
        }

        try{
            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->user_id = auth()->user()->id;
            $post->save();

            return response()->json([
                'message' => 'user added successfully',
                // 'post_data' => $post
            ], 200);
        }catch(\Exception $th){
            return response()->json(['error' => $th->getMessage()], 403);
        }
        
    }

    public function editPost1(Request $request){

        $validated = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
            'post_id' => 'required|integer'
        ]);

        if($validated->fails())
        {
            return response()->json([$validated->errors()]);
        }

        try{
            $postData = Post::find($request->post_id);

            $updatedData = $postData->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json([
                'message' => 'post updated successfully',
                'post_updated' => $updatedData,
            ]);
        }catch(\Exception $th){
            return response()->json(['error' => $th->getMessage()], 403);
        }
        
    }

    public function editPost2(Request $request, $post_id){

        $validated = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
            'post_id' => 'required|integer'
        ]);

        if($validated->fails())
        {
            return response()->json([$validated->errors()]);
        }

        try{
            $postData = Post::find($post_id);

            $updatedData = $postData->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json([
                'message' => 'post updated successfully',
                'post_updated' => $updatedData,
            ]);
        }catch(\Exception $th){
            return response()->json(['error' => $th->getMessage()], 403);
        }
        
    }

    //get all the post
    public function getAllPost()
    {
        try{
            $posts = Post::all();
            return response()->json([
                'message' => 'post show successfully',
                'posts' => $posts,
            ], 200);

        }catch(\Exception $th){
            return response()->json([
                'error' => $th->getMessage()
            ]);
        }
        
        
    }

    //get single post 
    public function getSinglePost($post_id)
    {
        try{
            //  $post = Post::find($post_id);
            $post = Post::with('user', 'comment', 'like')->where('id', $post_id)->first();
            return response()->json([
                'post' => $post
            ]);
            
        }catch(\Exception $th){
            return response()->json([
                'error' => $th->getMessage()
            ]);
        }
    }

    //delete the post
    public function deletePost($post_id)
    {
        try{
            $post = Post::findOrFail($post_id);
            $post->delete();
            return response()->json([
                'message' => 'post deleted sucessfully',
            ]);
        }catch(\Exception $th){
            return response()->json([
                'error' => $th->getMessage()
            ]);
        }
        
        
    }
}
