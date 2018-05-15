<?php

namespace App\Forms\Backend\User;

use Kris\LaravelFormBuilder\Form;
use App\Models\Event;
use DB;

class RegisterToEventForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('number', 'text');

        $this->add('submit', 'submit', ['label' => __('Save')]);
    }
}
