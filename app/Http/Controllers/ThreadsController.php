<?php

namespace App\Http\Controllers;

use App\Thread;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('store');
    }

    public function index()
    {
        $threads = Thread::all();

        return view('threads.index', compact('threads'));
    }

    public function show(Thread $thread)
    {
        return view('threads.show', compact('thread'));
    }

    public function store()
    {
        $this->validate(request(), [
            'title' => 'required',
            'body' => 'required'
        ]);

        Thread::create([
            'user_id' => auth()->id(),
            'title' => request()->title,
            'body' => request()->body
        ]);

        return back();
    }
}
