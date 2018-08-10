<?php

namespace App\Forms\Backend\Property;

use Kris\LaravelFormBuilder\Form;

class BidForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('number', 'number', ['label' => __('Number (# Paleta)')])
            ->add('offer', 'number', ['rules' => 'required'])
            ->add('submit', 'submit', ['label' => __('Save')]);
    }
}
