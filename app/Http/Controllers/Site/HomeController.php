<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Queries\PublishedApps;
use App\Queries\SiteContent;
use App\Queries\VisibleContactLinks;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(PublishedApps $apps, SiteContent $site, VisibleContactLinks $contacts): View
    {
        return view('site.home', [
            'featuredApps' => $apps->featured(),
            'menuApps' => $apps->menu(),
            'settings' => $site->settings(),
            'content' => $site->homepage(),
            'services' => $site->services(),
            'projects' => $site->projects(),
            'homeContacts' => $contacts->forLocation('homepage'),
            'footerContacts' => $contacts->forLocation('footer'),
            'headerContacts' => $contacts->forLocation('header'),
        ]);
    }
}
