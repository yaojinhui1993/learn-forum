<div class="panel panel-default" id={{ 'reply-' . $reply->id }}>
    <div class="panel-heading">
            <div class="level">
            <h5 class="flex">
                <a href="{{ route('profile', $reply->owner->name) }}" >
                     {{ $reply->owner->name }}
                </a> said {{ $reply->created_at->diffForHumans() }}...
            </h5>
            <form action="/replies/{{ $reply->id }}/favorites" method="POST">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                    {{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count) }}
                </button>
            </form>
        </div>

    </div>
    <div class="panel-body">
        <article>
            <div>{{ $reply->body }}</div>
        </article>
    </div>

    @can('update', $reply)
    <div class="panel-footer">
        <form action="/replies/{{ $reply->id }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('delete') }}
            <button type="submit" class="btn btn-danger btn-xs">Delete</button>
        </form>
    </div>

    @endcan
</div>