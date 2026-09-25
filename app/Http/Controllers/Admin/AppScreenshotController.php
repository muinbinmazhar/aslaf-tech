<?php

namespace App\Http\Controllers\Admin;

use App\Actions\DeleteAppScreenshot;
use App\Actions\ReorderAppScreenshots;
use App\Actions\StoreAppScreenshots;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReorderAppScreenshotsRequest;
use App\Http\Requests\Admin\StoreAppScreenshotsRequest;
use App\Models\App;
use App\Models\AppScreenshot;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AppScreenshotController extends Controller
{
    public function index(App $app): View
    {
        $app->load('screenshots');
        return view('admin.apps.screenshots.index', compact('app'));
    }

    public function store(StoreAppScreenshotsRequest $request, App $app, StoreAppScreenshots $action): RedirectResponse
    {
        $action->handle($app, $request->file('screenshots'));
        return back()->with('success', 'Screenshots uploaded.');
    }

    public function reorder(ReorderAppScreenshotsRequest $request, App $app, ReorderAppScreenshots $action): RedirectResponse
    {
        $action->handle($app, $request->validated('screenshot_ids'));
        return back()->with('success', 'Screenshot order saved.');
    }

    public function destroy(App $app, AppScreenshot $screenshot, DeleteAppScreenshot $action): RedirectResponse
    {
        $action->handle($app, $screenshot);
        return back()->with('success', 'Screenshot deleted.');
    }
}
