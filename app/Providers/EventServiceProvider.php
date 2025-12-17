<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        \App\Events\AssessmentAssignedByAdmin::class => [
            \App\Listeners\NotifyAsesorOfAssignment::class,
        ],
        \App\Events\AssessmentCompleted::class => [
            \App\Listeners\NotifyAsesorOfCompletion::class,
        ],
        \App\Events\AssessmentReviewed::class => [
            \App\Listeners\NotifyValidatorOfReview::class,
        ],
        \App\Events\AssessmentValidated::class => [
            \App\Listeners\NotifyUsersOfValidation::class,
        ],
    ];

    /**
     * The subscribers to register.
     *
     * @var array
     */
    protected $subscribe = [
        // \App\Listeners\UserEventSubscriber::class,
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
