<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Queries\PublishedApps;
use App\Queries\SiteContent;
use App\Queries\VisibleContactLinks;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function show(string $slug, SiteContent $site, PublishedApps $apps, VisibleContactLinks $contacts): View
    {
        return view('site.portfolio.show', [
            'project' => $site->projectBySlug($slug),
            'settings' => $site->settings(),
            'menuApps' => $apps->menu(),
            'footerContacts' => $contacts->forLocation('footer'),
            'headerContacts' => $contacts->forLocation('header'),
        ]);
    }
}
