<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Queries\PublishedApps;
use App\Queries\SiteContent;
use App\Queries\VisibleContactLinks;
use App\Support\YouTubeVideo;
use Illuminate\View\View;

class AppController extends Controller
{
    public function index(PublishedApps $apps, SiteContent $site, VisibleContactLinks $contacts): View
    {
        return view('site.apps.index', [
            'apps' => $apps->paginated(),
            'menuApps' => $apps->menu(),
            'settings' => $site->settings(),
            'footerContacts' => $contacts->forLocation('footer'),
            'headerContacts' => $contacts->forLocation('header'),
        ]);
    }

    public function show(string $slug, PublishedApps $apps, SiteContent $site, VisibleContactLinks $contacts): View
    {
        $app = $apps->findBySlug($slug);

        return view('site.apps.show', [
            'app' => $app,
            'videoEmbedUrl' => YouTubeVideo::embedUrl($app->youtube_url),
            'menuApps' => $apps->menu(),
            'settings' => $site->settings(),
            'footerContacts' => $contacts->forLocation('footer'),
            'headerContacts' => $contacts->forLocation('header'),
        ]);
    }
}
