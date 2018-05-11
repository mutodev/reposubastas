<?php

namespace App\Forms\Frontend\User;

use Kris\LaravelFormBuilder\Form;

class RegisterForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('name', 'text', ['label' => __('Name'), 'rules' => 'required'])
            ->add('email', 'text', ['label' => __('Email'), 'rules' => 'required|string|email|max:255|unique:users'])
            ->add('password', 'password', ['label' => __('Password'), 'rules' => 'required|string|min:6|confirmed'])
            ->add('password_confirmation', 'password', ['label' => __('Password Confirmation'), 'rules' => 'required'])
            ->add('martial_status', 'text', ['label' => __('Marital Status')])
            ->add('spouse_name', 'text', ['label' => __('Spouse Name')])
            ->add('address', 'text', ['label' => __('Address')])
            ->add('city', 'text', ['label' => __('City')])
            ->add('postal_code', 'text', ['label' => __('Postal Code')])
            ->add('phone', 'text', ['label' => __('Phone')])
            ->add('type', 'select', [
                'choices' => [
                    'Broker' => __('Broker'),
                    'Client' => __('Client')
                ],
                'label' => __('Type')
            ])
            ->add('broker_name', 'text', ['label' => __('Broker Name')])
            ->add('company', 'text', ['label' => __('Company')])
            ->add('license', 'text', ['label' => __('License')]);

        $this->add('captcha', 'captcha', ['label' => __('Verification code'), 'rules' => 'required|captcha', 'error_messages' => [
            'captcha.captcha' => __('Invalid')
        ]]);

        $this->add('submit', 'submit', ['label' => __('Register')]);
    }
}
