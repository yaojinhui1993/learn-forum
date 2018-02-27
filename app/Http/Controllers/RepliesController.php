<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;

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
        return back();
    }
}
