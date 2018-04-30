<?php

namespace App\Forms\Backend\User;

use Kris\LaravelFormBuilder\Form;
use App\Models\Event;
use DB;

class RegisterToEventForm extends Form
{
    public function buildForm()
    {
        $modelId = $this->getRequest()->route()->parameter('model')->id;

        $userEvent = DB::table('user_event')
            ->where('user_id', $modelId)
            ->orderBy('number', 'desc')
            ->get()->toArray();

        $activeEventIds = array_column($userEvent, 'event_id');

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
