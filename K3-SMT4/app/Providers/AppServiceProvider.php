<?php

namespace App\Providers;

use App\Models\FormSubmission;
use App\Models\K3Document;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Relation::enforceMorphMap([
            'document' => K3Document::class,
            'form_submission' => FormSubmission::class,
        ]);

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
