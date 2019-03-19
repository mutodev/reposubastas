<?php

namespace App\Providers;

use App\Models\PropertyStatusLog;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        \App\Models\Property::updated(function($property)
        {
            $newValues = $property->getDirty();

            //Status changed
            if (in_array('status_id', array_keys($property->getDirty()))) {
                //Create log
                $original = $property->getOriginal();
                $model = new PropertyStatusLog();
                $model->fill([
                    'property_id' => $property->id,
                    'old_status_id' => $original['status_id'],
                    'new_status_id' => $newValues['status_id'],
                    'optioned_by' => $original['optioned_by'],
                    'payload' => json_encode(array_merge($original, ['cancel_reason' => $property->cancel_reason]))
                ]);
                $model->save();
            }
        });
    }
}
