<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        $comment = new Comment();
        $comment->body = $request->input('comment');
        $comment->user()->associate(Auth::user());
        $comment->event()->associate($request->input('event'));
        $comment->save();

        return redirect()
            ->back()
            ->with('comment-success', 'Created comment');
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'comment' => 'required',
        ]);

        $comment = Comment::find($id);
        $this->authorize('update', $comment);

        $comment->body = $request->input('comment');
        $comment->save();

        return redirect()
            ->back()
            ->with('comment-success', 'Edited comment');
    }

    public function delete($id)
    {
        $comment = Comment::find($id);
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()
            ->back()
            ->with('comment-success', 'Delete comment');
    }
}
