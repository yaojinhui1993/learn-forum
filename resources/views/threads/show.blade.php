@extends('layouts.app') 
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>
                        <a href="#">{{ $thread->creator->name }}</a> posted: {{ $thread->title }}
                    </h4>
                </div>
                <div class="panel-body">
                    <article>
                        <div>{{ $thread->body }}</div>
                    </article>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @foreach($thread->replies as $reply)
                @include('threads.reply')
            @endforeach
        </div>
    </div>

    @if(auth()->check())
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form action="{{ $thread->path() . '/replies' }}" method="POST">
                {{ csrf_field() }}
                <div class="form-group">
                  <textarea class="form-control" name="body" rows="5" placeholder="Having something to say?"></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    @else 
    <div class="text-center">
        <p>Please <a href="{{ route('login') }}">sign in</a> to participate the discussion</p>
    </div>

    @endif
</div>
@endsection