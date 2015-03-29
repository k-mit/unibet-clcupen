@extends('admin')

@section('content')
    <h1>Results for round {{$round}}</h1>
    <table class="results_table">
        <thead>
        <tr>
            <th> # </th>
            <th> Name </th>
            <th> score </th>
            <th> email </th>
            <th> email2 </th>
            <th> tiebreaker </th>
        </tr>
        </thead>
        @foreach($highscore as $highscore_row)
            <tr>
                <td>{{$highscore_row->num}}</td>
                <td>{{$highscore_row->name}}</td>
                @if($round<10)
                    <td class="centered">{{$highscore_row->score}}</td>
                @else
                    <td  class="centered">{{$highscore_row->total_score}}</td>
                @endif
                <td>{{$highscore_row->email}}</td>
                <td>{{$highscore_row->email2}}</td>
                <td class="centered">{{$highscore_row->tiebreaker}}</td>
            </tr>
        @endforeach
    </table>
@endsection