<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($channelId, Thread $thread)
    {
        // validate
        $this->validate(request(), [
            'body' => 'required'
        ]);

        // save
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => request()->body,
        ]);

        // redirect
        return back()->with('flash', 'You reply has been left!');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update(['body' => request('body')]);

    }
}
