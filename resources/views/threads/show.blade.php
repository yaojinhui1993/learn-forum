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
</div>
@endsection