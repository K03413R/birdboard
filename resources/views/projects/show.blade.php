@extends('layouts.app')

@section('content')
    <a href="/projects">All Projects</a>
    <h1>{{$project->title}}</h1>
    <div>{{$project->description}}</div>
@endsection
