<?php

namespace App\Forms\Backend\Event;

use Kris\LaravelFormBuilder\Form;

class EditForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('name', 'text', ['rules' => 'required'])
            ->add('start_at', 'datetime-local', ['rules' => 'required'])
            ->add('live_at', 'datetime-local', ['rules' => 'required'])
            ->add('end_at', 'datetime-local', ['rules' => 'required'])
            ->add('location', 'text', ['rules' => 'required'])
            ->add('is_active', 'checkbox', [
                'value' => 1,
                'checked' => true,
                'label' => __('Is active?')
            ])
            ->add('submit', 'submit', ['label' => __('Save')]);
    }
}
