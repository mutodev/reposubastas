<?php

namespace App\Forms\Frontend\Property;

use Kris\LaravelFormBuilder\Form;

class OfferForm extends Form
{
    protected $formOptions = [
        'autocomplete' => 'off',
        'novalidate'   => 'novalidate'
    ];

    public function buildForm()
    {
        $typeOptions = [
            'Financed' => __('Financed'),
            'Cash' => __('Cash')
        ];

        if ($this->data['is_cash_only'] || (isset($this->data['property']) && $this->data['property']->type->slug === 'MORTGAGE-NOTE')) {
            unset($typeOptions['Financed']);
        }

        $this->add('offer', 'number', ['label' => false, 'rules' => 'required', 'attr' => ['step' => 250]]);
        $this->add('type', 'select', [
            'rules' => 'required',
            'choices' => $typeOptions,
            'label' => __('Cash/Financed')
        ]);
        $this->add('accept_terms', 'accept', ['value' => 1, 'rules' => 'accepted', 'label' => __('I agree to REPOSUBASTA :terms', [
            'terms' => '<a href="'.route('frontend.page', ['locale' => \App::getLocale(), 'pageSlug' => 'terms']).'">'.__('Terms').'</a>'
        ])]);
        $this->add('submit', 'submit', ['label' => __('Submit Offer'), 'attr' => [
            'class' => 'form-control border-0' . ((isset($this->data['property']) && $this->data['property']->type->slug === 'MORTGAGE-NOTE') ? ' bg-warning' : ' bg-light-blue')
        ]]);
    }
}
