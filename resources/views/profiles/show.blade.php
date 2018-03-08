@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row col-md-8 col-md-offset-2">
        <div class="page-header">
            <h1>
                {{ $profileUser->name }}
            </h1>
        </div>
        @foreach ($activities as $date => $activity)
            <div class="page-header">
                <h3>{{ $date }}</h3>
            </div>
            @foreach($activity as $record)
                @include("profiles.activities.{$record->type}", ['activity' => $record])
            @endforeach
        @endforeach
         {{--  {{ $threads->links() }}  --}}

    </div>
</div>
@endsection