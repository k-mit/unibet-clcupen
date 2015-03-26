@extends('app')

@section('content')

    {!! Form::open(['url'=>'/notifyAll','method'=>'post']) !!}
    <h2>Send notifications to all</h2>
    <table>


    </table>
    @foreach($notifications_list as $notification_row)
        {{$notification_row}}
    @endforeach

    {!! Form::close() !!}
@endsection