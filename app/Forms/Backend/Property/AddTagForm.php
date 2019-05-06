<?php

namespace App\Forms\Backend\Property;

use Kris\LaravelFormBuilder\Form;
use App\Models\PropertyTag;

class AddTagForm extends Form
{
    public function buildForm()
    {
        $events = PropertyTag::all()->toArray();
        $eventsOptions = [];
        foreach ($events as $k => $event) {
            $eventsOptions[$event['id']] = $event['name_en'];
        }

        $this
            ->add('tag_id', 'select', [
                'choices' => $eventsOptions,
                'label' => __('Tag')
            ]);

        $this->add('submit', 'submit', ['label' => __('Save')]);
    }
}
