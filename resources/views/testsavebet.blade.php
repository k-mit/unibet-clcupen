@extends('app')

@section('content')

    {!! Form::open(['url'=>'/saveBet','method'=>'post']) !!}
    <!-- User_id Form Input -->
    <div class="form-group">
        {!! Form::label('user_id', 'User_id:',['class' => 'form-label']) !!}
        {!! Form::text('user_id', null, ['class' => 'form-control']) !!}
    </div>
@for($count=1;$count<5;$count++)
    <!-- Bet_team1_{{$count}} Form Input -->
    <div class="form-group">
        {!! Form::label('bet_team1_'.$count, 'Bet_team1_'.$count.':',['class' => 'form-label']) !!}
        {!! Form::text('bet_team1_'.$count, null, ['class' => 'form-control']) !!}
    </div>
    <!-- Bet_team2_{{$count}} Form Input -->
    <div class="form-group">
        {!! Form::label('bet_team2_'.$count, 'Bet_team2_'.$count.':',['class' => 'form-label']) !!}
        {!! Form::text('bet_team2_'.$count, null, ['class' => 'form-control']) !!}
    </div>
    <!-- Match_id_{{$count}} Form Input -->
    <div class="form-group">
        {!! Form::label('match_id_'.$count, 'Match_id_'.$count.':',['class' => 'form-label']) !!}
        {!! Form::text('match_id_'.$count, null, ['class' => 'form-control']) !!}
    </div>
    @endfor
    <!-- Save Form Submit -->
    <div class="form-group">
        {!! Form::Submit('save', ['class' => 'btn btn-primary form-control']) !!}
    </div>

    {!! Form::close() !!}
@endsection