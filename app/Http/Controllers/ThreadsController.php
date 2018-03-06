<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use App\Filters\ThreadFilters;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        return view('threads.index', compact('threads'));
    }

    public function show($channel, Thread $thread)
    {
        return view(
            'threads.show',
            [
                'thread' => $thread,
                'replies' => $thread->replies()->paginate(20)
            ]
        );
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

    public function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);
        if ($channel->exists) {
            $threads = $threads->where('channel_id', $channel->id);
        }

        return $threads = $threads->get();
    }
}
