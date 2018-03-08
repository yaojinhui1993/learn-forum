@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="level">
                        <span class="flex">
                            <a href="{{ route('profile', $thread->creator->name) }}">{{ $thread->creator->name }}</a> posted: {{ $thread->title }}
                        </span>

                        @can('update', $thread)
                            <form action="{{ $thread->path() }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE')}}

                                <button type="submit" class="btn btn-link">Delete Thread</button>
                            </form>
                        @endcan
                    </h4>
                </div>
                <div class="panel-body">
                    <article>
                        <div>{{ $thread->body }}</div>
                    </article>
                </div>
            </div>
            @foreach($replies as $reply)
                @include('threads.reply')
            @endforeach

            {{ $replies->links() }}

            @if(auth()->check())
            <form action="{{ $thread->path() . '/replies' }}" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                    <textarea class="form-control" name="body" rows="5" placeholder="Having something to say?"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
            @else
            <div class="text-center">
                <p>
                    Please <a href="{{ route('login') }}">sign in</a> to participate the discussion
                </p>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p>
                        This thread was published {{ $thread->created_at->diffForHumans() }}
                        by <a href="#">{{ $thread->creator->name }}</a>,
                        and currently has {{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}.
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection