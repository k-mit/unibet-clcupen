@extends('admin')

@section('content')
    @if($round<10)
        <h1>Results for round {{$round}}</h1>
        <a href="{{route('excel',['round'=>$round])}}" target="export"><h3>Excel export</h3></a>
    @else
        <h1>Result all rounds (including invite scores)</h1>
        <a href="{{route('excel',['round'=>$round])}}" target="export"><h3>Excel export</h3></a>
    @endif

    <table class="results_table">
        <thead>
        <tr>
            <th> #</th>
            <th> Name</th>
            <th>Score</th>
            <th width="40" class="centered"> Scores for invites</th>
            <th width="40" class="centered">Total score</th>
            <th> email</th>
            <th> email2</th>
            <th> tiebreaker 1 <span style="color: #008000">({{$tiebreakerResults[0]->result}})</span></th>
            <th> tiebreaker 2 <span style="color: #008000">({{$tiebreakerResults[1]->result}})</span></th>
            <th> tiebreaker 3 <span style="color: #008000">({{$tiebreakerResults[2]->result}})</span></th>
            <th> tiebreaker 4 <span style="color: #008000">({{$tiebreakerResults[3]->result}})</span></th>
            <th> tiebreaker all <span style="color: #008000">({{$tiebreakerResults[0]->result+$tiebreakerResults[1]->result+$tiebreakerResults[2]->result+$tiebreakerResults[3]->result}})</span></th>
            <th width="70"> tiebreaker Difference</th>
        </tr>
        </thead>
        @foreach($highscore as $highscore_row)
            <tr>
                <td>{{$highscore_row->num}}</td>
                <td>{{$highscore_row->name}}</td>
                <td class="centered">{{$highscore_row->score}}</td>
                <td class="centered">{{$highscore_row->extra_score}}</td>
                <td class="centered">{{$highscore_row->total_score}}</td>
                <td>{{$highscore_row->email}}</td>
                <td>{{$highscore_row->email2}}</td>
                <td class="centered">@if(isset($highscore_row->tiebreaker_1)){{$highscore_row->tiebreaker_1}}@endif</td>
                <td class="centered">@if(isset($highscore_row->tiebreaker_2)){{$highscore_row->tiebreaker_2}}@endif</td>
                <td class="centered">@if(isset($highscore_row->tiebreaker_3)){{$highscore_row->tiebreaker_3}}@endif</td>
                <td class="centered">@if(isset($highscore_row->tiebreaker_4)){{$highscore_row->tiebreaker_4}}@endif</td>
                <td class="centered">{{$highscore_row->tiebreaker_1+$highscore_row->tiebreaker_2+$highscore_row->tiebreaker_3+$highscore_row->tiebreaker_4}}</td>
                <td class="centered">{{$highscore_row->tiebreaker_diff}}</td>
            </tr>
        @endforeach
    </table>
@endsection