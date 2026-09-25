<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('contacts:import-legacy', function (\App\Actions\ImportLegacyContacts $action) {
    $this->info($action->handle().' contact links imported.');
})->purpose('Import saved Website Settings contact details once');
