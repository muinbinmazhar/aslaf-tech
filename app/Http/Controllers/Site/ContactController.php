<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\ContactRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class ContactController extends Controller
{
    public function __invoke(ContactRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('company');
        $fingerprint = 'contact:'.hash('sha256', implode('|', [$data['email'], $data['subject'], $data['message']]));

        if (Cache::add($fingerprint, true, now()->addMinutes(2))) {
            try {
                ContactMessage::create($data);
            } catch (\Throwable $exception) {
                Cache::forget($fingerprint);
                throw $exception;
            }
        }

        return redirect()->to(route('home').'#contact')
            ->with('contact_success', 'Thank you. Your message has been received.');
    }
}
