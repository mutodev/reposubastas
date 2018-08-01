<?php

namespace App\Forms\Frontend\User;

use Kris\LaravelFormBuilder\Form;

class RegisterForm extends Form
{
    protected $formOptions = [
        'autocomplete' => 'off',
        'novalidate'   => 'novalidate'
    ];

    public function buildForm()
    {
        $isBackend = $this->getData('isBackend', false);
        $required = $isBackend ? 'nullable|' : 'required|';
        $emailUnique = $this->model ? '|unique:users' : '';

        $this
            ->add('name', 'text', ['label' => __('Name'), 'rules' => 'required'])
            ->add('email', 'text', ['label' => __('Email'), 'rules' => "{$required}string|email|max:255{$emailUnique}"])
            ->add('password', 'password', ['label' => __('Password'), 'rules' => "{$required}string|min:6|confirmed"])
            ->add('password_confirmation', 'password', ['label' => __('Password Confirmation'), 'rules' => "{$required}"])
            ->add('martial_status', 'select', [
                'rules' => 'required',
                'choices' => [
                    'Single' => __('Single'),
                    'Married' => __('Married')
                ],
                'label' => __('Marital Status')
            ])
            ->add('spouse_name', 'text', ['label' => __('Spouse Name')])
            ->add('address', 'text', ['label' => __('Address')])
            ->add('city', 'text', ['label' => __('City')])
            ->add('postal_code', 'text', ['label' => __('Postal Code')])
            ->add('phone', 'text', ['label' => __('Phone'), 'rules' => 'required'])
            ->add('type', 'select', [
                'choices' => [
                    'Broker' => __('Yes'),
                    'Client' => __('No')
                ],
                'label' => __('Have broker?')
            ])
            ->add('broker_name', 'text', ['label' => __('Broker Name')])
            ->add('company', 'text', ['label' => __('Company')])
            ->add('license', 'text', ['label' => __('License')])
            ->add('phone2', 'text', ['label' => __('Broker Phone')]);

        if (!$isBackend) {
            $this->add('captcha', 'captcha', ['label' => __('Verification code'), 'rules' => 'required|captcha', 'error_messages' => [
                'captcha.captcha' => __('Invalid')
            ]]);
            $this->add('accept_terms', 'accept', ['value' => 1, 'rules' => 'accepted', 'label' => __('I agree to REPOSUBASTA :terms', [
                'terms' => '<a href="'.route('frontend.page', ['locale' => \App::getLocale(), 'pageSlug' => 'terms']).'">'.__('Terms and Conditions').'</a>',
                'policy' => '<a href="'.route('frontend.page', ['locale' => \App::getLocale(), 'pageSlug' => 'policy']).'">'.__('Privacy Policy').'</a>'
            ])]);
        } else {
            $this->add('number', 'text', ['label' => __('Number (# Paleta)'), 'rules' => 'required']);
        }

        $this->add('submit', 'submit', ['label' => __('Register')]);
    }
}
