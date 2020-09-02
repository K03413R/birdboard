<div class="card p-5 flex flex-col" style="height:200px">
    <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-blue-500 pl-4 ">
        <a href="{{$project->path()}}">
            {{$project->title}}
        </a>
    </h3>
    <div class="text-gray-800 flex-1">{{\Illuminate\Support\Str::limit($project->description, 100)}}</div>

    <footer>
        <form action="{{$project->path()}}" class="text-right" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs">Delete</button>
        </form>
    </footer>

</div>

