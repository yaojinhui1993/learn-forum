<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div class="panel panel-default" id={{ 'reply-' . $reply->id }}>
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a href="{{ route('profile', $reply->owner->name) }}">
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
            <div v-if="editing">
                <div class="form-group">
                    <textarea name="body" id="body" class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-primary btn-xs" @click="update">update</button>
                <button class="btn btn-primary btn-link" @click="editing = false">cancel</button>

            </div>
            <div v-else v-text="body">
            </div>
        </div>
        @can('update', $reply)
        <div class="panel-footer level">
            <button class="btn btn-default btn-xs mr-1" @click="editing = true">Edit</button>
            <button type="button" class="btn btn-danger btn-xs" @click="destroy">Delete</button>
        </div>
        @endcan
    </div>
</reply>