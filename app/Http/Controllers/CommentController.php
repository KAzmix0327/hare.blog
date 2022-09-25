<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Post $post)
    {
        return view('comments.create', compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Reque  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request, Post $post)
    {
        $comment = new Comment($request->all());

        $comment->user_id = $request->user()->id;
        $comment->post_id = $post->id;

        // トランザクション開始
        // DB::beginTransaction();
        try {
        //登録
        $post->comments()->save($comment);
        // トランザクション終了(成功)
            // DB::commit();
        } catch (\Throwable $th) {
            // トランザクション終了(失敗)
            // DB::rollback();
            return back()->withInput()->withErrors($th->getMessage());
        }
            //throw $th;
        return redirect()->route('posts.show',$post)
        ->with('notice', 'コメントを登録しました');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post,comment $comment)
    {
        return view('comments.edit', compact('post', 'comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requ  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(CommentRequest $request, Post $post, Comment $comment)
    {

        if ($request->user()->cannot('update', $comment)) {
            return redirect()->route('posts.show', $post)
                ->withErrors('自分のコメント以外は更新できません');
        }
        
        $comment ->fill($request->all());

        // $comment->user_id = $request->user()->id;
        // // $comment->post_id = $post->id;

        try {
        //登録
        $comment->save();

        } catch (\Throwable $th) {

            return back()->withInput()->withErrors($th->getMessage());
        }
            //throw $th;
        return redirect()->route('posts.show',$post)
        ->with('notice', 'コメントを更新しました');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Post $post,Comment $comment)
    {
        if ($request->user()->cannot('delete', $comment)) {
            return redirect()->route('posts.show', $post)
                ->withErrors('自分のコメント以外は削除できません');
        }
        try {
        //登録
        $comment->delete();

        } catch (\Exception $e) {

            return back()->withInput()->withErrors($e->getMessage());
        }
            //throw $th;
        return redirect()->route('posts.show',$post)
        ->with('notice', 'コメントを削除しました');

    }
}
