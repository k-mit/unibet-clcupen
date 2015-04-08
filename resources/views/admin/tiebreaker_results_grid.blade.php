@extends('admin')

@section('content')
    <h1>Results</h1>
    (Round 10 should reflect all the other rounds combined)
    <p>

        {!! $grid !!}

    </p>
@endsection