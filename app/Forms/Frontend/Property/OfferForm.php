<?php

namespace App\Forms\Frontend\Property;

use Kris\LaravelFormBuilder\Form;

class OfferForm extends Form
{
    public function buildForm()
    {
        $this->add('offer', 'number', ['label' => false, 'rules' => 'required']);
        $this->add('submit', 'submit', ['label' => __('Submit Offer'), 'attr' => [
            'class' => 'form-control bg-light-blue border-0'
        ]]);
    }
}
