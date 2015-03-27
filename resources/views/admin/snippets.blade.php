@extends('app')

@section('content')
    <h2 style="text-align: center">Update text snippets</h2>
    @foreach($snippets_list as $snippet)
        {!! Form::open(['url'=>'/admin/saveSnippet']) !!}
        <!-- Id Form Input -->
        <div class="form-group">
            {!! Form::hidden('id', $snippet['id'], ['class' => 'form-control']) !!}
        </div>
        <!-- snippet_name Form Input -->
        <div class="form-group">
            {!! Form::hidden('snippet_name', $snippet['snippet_name'], ['class' => 'form-control']) !!}
        </div>

        <!-- Snippet_value Form Input -->

        <div class="form-group">
            <h3>{!! Form::label('snippet_value', $snippet['snippet_name'],['class' => 'form-label']) !!}</h3> <strong>{{$snippet['description']}}</strong>
            {!! Form::textarea('snippet_value', $snippet['snippet_value'], ['class' => 'form-control editable','rows'=>'4']) !!}
        </div>

        <!-- Save Snippet Form Submit -->
        <div class="form-group">
            {!! Form::Submit('Save Snippet', ['class' => 'btn btn-primary form-control']) !!}
        </div>

        {!! Form::close() !!}
        <hr />
        <br />
    @endforeach
@endsection