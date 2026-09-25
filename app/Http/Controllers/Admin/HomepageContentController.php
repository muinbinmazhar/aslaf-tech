<?php

namespace App\Http\Controllers\Admin;

use App\Actions\SaveHomepageContent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomepageContentRequest;
use App\Queries\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomepageContentController extends Controller
{
    public function edit(SiteContent $site): View
    {
        return view('admin.website.homepage', ['content' => $site->homepage()]);
    }

    public function update(HomepageContentRequest $request, SaveHomepageContent $action): RedirectResponse
    {
        $action->handle($request->validated()['content'] ?? []);

        return back()->with('success', 'Homepage content saved.');
    }
}
