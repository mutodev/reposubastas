<?php

namespace App\Forms\Frontend\Property;

use Kris\LaravelFormBuilder\Form;

class OfferForm extends Form
{
    public function buildForm()
    {
        $this->add('offer', 'number', ['label' => false, 'rules' => 'required']);
        $this->add('captcha', 'captcha', ['label' => __('Verification code'), 'rules' => 'required|captcha', 'error_messages' => [
            'captcha.captcha' => __('Invalid')
        ]]);
        $this->add('submit', 'submit', ['label' => __('Submit Offer'), 'attr' => [
            'class' => 'form-control bg-light-blue border-0'
        ]]);
    }
}
