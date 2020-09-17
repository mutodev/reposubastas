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

        if ($this->data['is_cash_only'] || ($this->data['property'] && $this->data['property']->type->slug === 'MORTGAGE-NOTE')) {
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
        $this->add('accept_temporary', 'accept', ['value' => 1, 'rules' => 'accepted', 'label' => __('Today we are facing problems with the Paypal payment method, so I promise to be a winning bidder to deliver certified check on or before 72 hours of 5% of the sale price plus 1% of premium value.')]);
        $this->add('submit', 'submit', ['label' => __('Submit Offer'), 'attr' => [
            'class' => 'form-control border-0' . (($this->data['property'] && $this->data['property']->type->slug === 'MORTGAGE-NOTE') ? ' bg-warning' : ' bg-light-blue')
        ]]);
    }
}
