<?php

namespace App\Forms\Backend\Property;

use Kris\LaravelFormBuilder\Form;
use App\Models\Event;
use DB;

class RegisterToEventForm extends Form
{
    public function buildForm()
    {
        $modelId = $this->getRequest()->route()->parameter('model')->id;

        $modelEvent = DB::table('property_event')
            ->where('property_id', $modelId)
            ->orderBy('number', 'desc')
            ->get()->toArray();

        $activeEventIds = array_column($modelEvent, 'event_id');

        $events = Event::all()->toArray();
        $eventsOptions = [];
        foreach ($events as $k => $event) {
            if (in_array($event['id'], $activeEventIds)) {
                continue;
            }

            $eventsOptions[$event['id']] = $event['name'];
        }

        $this
            ->add('event_id', 'select', [
                'choices' => $eventsOptions,
                'label' => __('Event')
            ])
            ->add('number', 'text');

        $this->add('submit', 'submit', ['label' => __('Save')]);
    }
}
