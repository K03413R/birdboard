
<div class="card p-5 flex flex-col mt-3">
    <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-blue-500 pl-4 ">
        Invite a User
    </h3>



    <form action="{{$project->path().'/invitations'}}" method="POST">
        @csrf
        <div class="mb-3">
            <input type="email" name="email" class="border border-gray-400 rounded w-full py-2 px-3" placeholder="Email address">
        </div>
        <button type="submit" class="button">Invite</button>
    </form>
    @include('errors', ['bag' => 'invitation'])
</div>
