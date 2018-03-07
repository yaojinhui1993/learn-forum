@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>
                {{ $profileUser->name }}
                <small>
                    Since {{ $profileUser->created_at->diffForHumans() }}
                </small>
            </h1>
        </div>
        @foreach ($threads as $thread)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="level">
                        <span class="flex">
                            <a href="#"> {{ $thread->creator->name }}</a> posted: {{ $thread->title }}
                        </span>
                        {{ $thread->created_at->diffForHumans() }}
                    </h4>
                </div>
                <div class="panel-body">
                    <article>
                        <div>{{ $thread->body }}</div>
                    </article>
                </div>
            </div>
        @endforeach
        {{ $threads->links() }}
    </div>
@endsection