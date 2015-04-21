@extends('admin')

@section('content')
    @if($round<10)
        <h1>Results for round {{$round}}</h1>
        <a href="{{route('excel',['round'=>$round])}}" target="export"><h3>Excel export</h3></a>
    @else
        <h1>Result all rounds (including invite scores)</h1>
    @endif

    <table class="results_table">
        <thead>
        <tr>
            <th> #</th>
            <th> Name</th>
            <th> score</th>
            <th> email</th>
            <th> email2</th>
            <th> tiebreaker 1</th>
            <th> tiebreaker 2</th>
            <th> tiebreaker 3</th>
            <th> tiebreaker 4</th>
        </tr>
        </thead>
        @foreach($highscore as $highscore_row)
            <tr>
                <td>{{$highscore_row->num}}</td>
                <td>{{$highscore_row->name}}</td>
                <td class="centered">{{$highscore_row->total_score}}</td>
                <td>{{$highscore_row->email}}</td>
                <td>{{$highscore_row->email2}}</td>
                <td class="centered">@if(isset($highscore_row->tiebreaker_1)){{$highscore_row->tiebreaker_1}}@endif</td>
                <td class="centered">@if(isset($highscore_row->tiebreaker_2)){{$highscore_row->tiebreaker_2}}@endif</td>
                <td class="centered">@if(isset($highscore_row->tiebreaker_3)){{$highscore_row->tiebreaker_3}}@endif</td>
                <td class="centered">@if(isset($highscore_row->tiebreaker_4)){{$highscore_row->tiebreaker_4}}@endif</td>
            </tr>
        @endforeach
    </table>
@endsection