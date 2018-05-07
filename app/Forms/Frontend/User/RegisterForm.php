<?php

namespace App\Forms\Frontend\User;

use Kris\LaravelFormBuilder\Form;

class RegisterForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('name', 'text', ['label' => __('Name')])
            ->add('email', 'text', ['label' => __('Email')])
            ->add('password', 'password', ['label' => __('Password')])
            ->add('type', 'select', [
                'choices' => [
                    'Broker' => __('Broker'),
                    'Client' => __('Client')
                ],
                'label' => __('Type')
            ])
            ->add('broker_name', 'text', ['label' => __('Broker Name')])
            ->add('martial_status', 'text', ['label' => __('Marital Status')])
            ->add('spouse_name', 'text', ['label' => __('Spouse Name')])
            ->add('address', 'text', ['label' => __('Address')])
            ->add('city', 'text', ['label' => __('City')])
            ->add('postal_code', 'text', ['label' => __('Postal Code')])
            ->add('phone', 'text', ['label' => __('Phone')])
            ->add('company', 'text', ['label' => __('Company')])
            ->add('license', 'text', ['label' => __('License')])
            ->add('expiration_date', 'date', ['label' => __('License Expiration')]);

        $this->add('submit', 'submit', ['label' => __('Register')]);
    }
}
