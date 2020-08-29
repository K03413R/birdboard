<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectRequest;
use App\Project;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    /**
     * Show all projects
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $projects = auth()->user()->projects;
        return view('projects.index', compact('projects'));
    }

    /**
     * Persists the new project
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function store()
    {
        //validate
        $project = auth()->user()->projects()->create($this->validateRequest());

        return redirect($project->path());
    }

    /**
     * Shows Details of a single project
     *
     * @param Project $project
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function show(Project $project)
    {
        $this->authorize('update', $project);
        return view('projects.show', compact('project'));
    }

    /**
     * Shows the create form
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Updates the project
     *
     * @param UpdateProjectRequest $request
     * @param Project $project
     * @return Application|RedirectResponse|Redirector
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return redirect($project->path());
    }

    /**
     * Shows the Edit form
     *
     * @param Project $project
     * @return Application|Factory|View
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));

    }

    /**
     * Validates the request
     *
     * @return array
     */
    protected function validateRequest(): array
    {
        return request()->validate([
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes' => 'nullable'
        ]);
    }
}
