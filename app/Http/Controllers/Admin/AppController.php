<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CreateApp;
use App\Actions\DeleteApp;
use App\Actions\UpdateApp;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAppRequest;
use App\Http\Requests\Admin\UpdateAppRequest;
use App\Models\App;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AppController extends Controller
{
    public function index(): View
    {
        return view('admin.apps.index', [
            'apps' => App::query()->withCount('screenshots')
                ->orderBy('sort_order')
                ->orderByDesc('created_at')
                ->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.apps.create');
    }

    public function store(StoreAppRequest $request, CreateApp $createApp): RedirectResponse
    {
        $createApp->handle($request->validated());

        return redirect()->route('admin.apps.index')
            ->with('success', 'নতুন App সফলভাবে তৈরি হয়েছে।');
    }

    public function edit(App $app): View
    {
        return view('admin.apps.edit', compact('app'));
    }

    public function update(UpdateAppRequest $request, App $app, UpdateApp $updateApp): RedirectResponse
    {
        $updateApp->handle($app, $request->validated());

        return redirect()->route('admin.apps.index')
            ->with('success', 'App সফলভাবে আপডেট হয়েছে।');
    }

    public function destroy(App $app, DeleteApp $deleteApp): RedirectResponse
    {
        $deleteApp->handle($app);

        return redirect()->route('admin.apps.index')
            ->with('success', 'App সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
