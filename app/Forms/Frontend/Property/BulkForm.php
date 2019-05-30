<?php

namespace App\Forms\Frontend\Property;

use Kris\LaravelFormBuilder\Form;

class BulkForm extends Form
{
    protected $formOptions = [
        'autocomplete' => 'off',
        'novalidate'   => 'novalidate'
    ];

    public function buildForm()
    {
        $this->add('name', 'text', ['label' => __('Name'), 'rules' => 'required']);
        $this->add('email', 'email', ['label' => __('Email'), 'rules' => 'required']);
        $this->add('phone', 'email', ['label' => __('Phone'), 'rules' => 'required']);
        $this->add('offer', 'number', ['label' => __('Offer'), 'rules' => 'required']);
        $this->add('type', 'select', [
            'rules' => 'required',
            'choices' => [
                'Financed' => __('Financed'),
                'Cash' => __('Cash'),
            ],
            'label' => __('Cash/Financed')
        ]);
        $this->add('accept_terms', 'accept', ['value' => 1, 'rules' => 'accepted', 'label' => __('I agree to REPOSUBASTA :terms', [
            'terms' => '<a href="'.route('frontend.page', ['locale' => \App::getLocale(), 'pageSlug' => 'terms']).'">'.__('Terms').'</a>'
        ])]);
        $this->add('submit', 'submit', ['label' => __('Submit Offer'), 'attr' => [
            'class' => 'form-control bg-light-blue border-0'
        ]]);
    }
}
