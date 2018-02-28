<?php

namespace App\Http\Controllers;

use App\Thread;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $threads = Thread::all();

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
