@extends('layouts.app') 
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>
                        Create a New Thread
                    </h4>
                </div>
                <div class="panel-body">
                    <form action="/threads" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group">
                          <label for="channel_id">Choose a channel</label>
                          <select class="form-control" name="channel_id" id="channel_id" selected>
                            <option value="">Choose one...</option>
                            @foreach($channels as $channel)
                                <option 
                                    value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected': '' }}>
                                    {{ $channel->name }}
                                </option>
                            @endforeach
                          </select>
                        </div>

                        <div class="form-group">
                            <label for="title">Title</label>
                            <input 
                                type="text"
                                class="form-control" 
                                name="title" 
                                id="title" 
                                aria-describedby="title" 
                                placeholder="The thread Title"
                                value="{{ old('title') }}"
                            >
                            </div>
        
                            <div class="form-group">
                            <label for="body">Body</label>
                            <textarea class="form-control" name="body" id="body" rows="8">{{ old('body') }}</textarea>
                            </div>
        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create thread</button>
                        </div>

                        @include('layouts.errors')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection