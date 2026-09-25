<?php

namespace App\Http\Controllers\Admin;

use App\Actions\SaveWebsiteSettings;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WebsiteSettingsRequest;
use App\Queries\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WebsiteSettingsController extends Controller
{
    public function edit(SiteContent $site): View
    {
        return view('admin.website.settings', ['settings' => $site->settings()]);
    }

    public function update(WebsiteSettingsRequest $request, SaveWebsiteSettings $action): RedirectResponse
    {
        $action->handle($request->validated());

        return back()->with('success', 'Website settings saved.');
    }
}
