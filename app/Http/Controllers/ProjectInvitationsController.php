<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectInvitationRequest;
use App\Project;
use App\User;

class ProjectInvitationsController extends Controller
{
    public function store(Project $project, ProjectInvitationRequest $request)
    {

        $user = User::where('email', $request->validated())->firstOrFail();

        $project->invite($user);

        return redirect($project->path());
    }
}
