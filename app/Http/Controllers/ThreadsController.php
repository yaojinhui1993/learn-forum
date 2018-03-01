<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use App\User;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Channel $channel = null)
    {
        if ($channel->exists) {
            $threads = $channel->threads()->latest();
        } else {
            $threads = Thread::latest();
        }

        if ($username = request('by')) {
            $threads = User::where('name', $username)->firstOrFail()->threads()->latest();
            // $user = User::where('name', $username)->firstOrFail();
            // $threads = Thread::where('user_id', $user->id)->latest();
        }

        $threads = $threads->get();

        return view('threads.index', compact('threads'));
    }

    public function show($channel, Thread $thread)
    {
        return view('threads.show', compact('thread'));
    }

    public function store()
    {
        $this->validate(request(), [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request()->channel_id,
            'title' => request()->title,
            'body' => request()->body
        ]);

        return redirect($thread->path());
    }

    public function create()
    {
        return view('threads.create');
    }
}
