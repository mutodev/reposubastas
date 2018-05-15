<?php

namespace App\Forms\Frontend\Property;

use Kris\LaravelFormBuilder\Form;

class OfferForm extends Form
{
    public function buildForm()
    {
        $this->add('offer', 'number', ['label' => false, 'rules' => 'required']);
        $this->add('accept_terms', 'accept', ['value' => 1, 'rules' => 'accepted', 'label' => __('I agree to REPOSUBASTA :terms and :policy', [
            'terms' => '<a href="'.route('frontend.page', ['locale' => \App::getLocale(), 'pageSlug' => 'terms']).'">'.__('Terms').'</a>',
            'policy' => '<a href="'.route('frontend.page', ['locale' => \App::getLocale(), 'pageSlug' => 'policy']).'">'.__('Privacy Policy').'</a>'
        ])]);
        $this->add('submit', 'submit', ['label' => __('Submit Offer'), 'attr' => [
            'class' => 'form-control bg-light-blue border-0'
        ]]);
    }
}
