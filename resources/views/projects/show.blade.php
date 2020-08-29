@extends('layouts.app')

@section('content')
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between w-full items-end">
            <p class="text-sm text-gray-700 font-normal">
                <a href="/projects" class="text-sm text-gray-700 font-normal no-underline">My Projects</a> / {{$project->title}}
            </p>
            <a href="{{$project->path().'/edit'}}" class="button">Edit Project</a>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <h2 class="text-lg text-gray-700 font-normal mb-3">Tasks</h2>
                    @foreach($project->tasks as $task)
                        <div class="card mb-3 p-5">
                            <form action="{{$task->path()}}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="flex justify-between w-full items-center">
                                    <input value="{{ $task->body }}" name="body" class="{{$task->completed ? 'text-gray-600' : ''}}">
                                    <input name="completed" type="checkbox" onchange="this.form.submit()" {{$task->completed ? 'checked' : ''}}>
                                </div>
                            </form>
                        </div>
                    @endforeach
                    <div class="card p-5">
                        <form action="{{$project->path() . '/tasks'}}" method="POST">
                            @csrf
                            <input class="w-full" placeholder="Begin adding tasks...." name="body">
                        </form>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg text-gray-700 font-normal mb-3">General Notes</h2>
                    <form action="{{$project->path()}}" method="POST">
                        @csrf
                        @method('PATCH')
                        <textarea
                            name="notes"
                            class="card w-full"
                            placeholder="Enter notes"
                        >{{$project->notes}}</textarea>

                        <button type="submit" class="button">Save</button>
                    </form>

                    @if($errors->any())
                        <div class="field mt-6">
                            @foreach($errors->all() as $error)
                                <li class="text-sm text-red-500">{{$error}}</li>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
            <div class="lg:w-1/4 px-3">
                <div class="card">
                    @include('projects.card')
                </div>
            </div>
        </div>
    </main>


@endsection
