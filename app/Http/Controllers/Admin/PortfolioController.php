<?php

namespace App\Http\Controllers\Admin;

use App\Actions\SavePortfolioProject;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PortfolioRequest;
use App\Models\PortfolioProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        return view('admin.website.portfolio.index', ['projects' => PortfolioProject::query()->orderBy('sort_order')->orderByDesc('id')->paginate(15)]);
    }

    public function create(): View
    {
        return view('admin.website.portfolio.form', ['project' => null]);
    }

    public function store(PortfolioRequest $request, SavePortfolioProject $action): RedirectResponse
    {
        $action->handle($request->validated());

        return redirect()->route('admin.portfolio.index')->with('success', 'Project created.');
    }

    public function edit(PortfolioProject $project): View
    {
        return view('admin.website.portfolio.form', compact('project'));
    }

    public function update(PortfolioRequest $request, PortfolioProject $project, SavePortfolioProject $action): RedirectResponse
    {
        $action->handle($request->validated(), $project);

        return redirect()->route('admin.portfolio.index')->with('success', 'Project updated.');
    }

    public function destroy(PortfolioProject $project, SavePortfolioProject $action): RedirectResponse
    {
        $action->delete($project);

        return back()->with('success', 'Project deleted.');
    }

    public function toggle(PortfolioProject $project): RedirectResponse
    {
        $project->update(['is_published' => ! $project->is_published]);

        return back()->with('success', 'Project status updated.');
    }
}
