@extends('admin')

@section('content')

    {!! Form::open(['url'=>'/admin/notifyAll','method'=>'post']) !!}
    <h2>Send notifications to all</h2>
    <table border="1" width="100%">
        <thead>
        <tr>
            <th> </th>
            <th>Template</th>
            <th>Created at</th>
        </tr>
        </thead>
    @foreach($notifications_list as $notification_row)
        <tr>
            <td align="center">{!! Form::radio('notification_id', $notification_row['id']) !!} </td>
            <td>{{$notification_row['template']}}</td>
            <td>{{$notification_row['created_at']}}</td>

        </tr>

    @endforeach
    </table><br><br>
    <!-- Send notification to all users Form Submit -->
    <div class="form-group">
        {!! Form::Submit('Send notification to all users', ['class' => 'btn btn-primary form-control']) !!}
    </div>
    {!! Form::close() !!}
    <hr />
    <h2>Create new notification</h2>
    {!! Form::open(['url'=>'/admin/saveNotification','method'=>'post']) !!}
    <!-- Template Form Input -->
    <div class="form-group">
        {!! Form::label('template', 'Template:',['class' => 'form-label']) !!}
        {!! Form::textarea('template', null, ['class' => 'form-control', 'maxlength' => "180",'rows' => '3']) !!}
    </div>
    <!-- Save new notification Form Submit -->
    <div class="form-group">
        {!! Form::Submit('Save new notification', ['class' => 'btn btn-primary form-control']) !!}
    </div>

    {!! Form::close() !!}
@endsection