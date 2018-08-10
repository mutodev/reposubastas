<?php

namespace App\Forms\Backend\User;

use Kris\LaravelFormBuilder\Form;

class EditForm extends Form
{
    protected $formOptions = [
        'autocomplete' => 'off',
        'novalidate'   => 'novalidate'
    ];

    public function buildForm()
    {
        $this
            ->add('name', 'text', ['label' => __('Name'), 'rules' => 'required'])
            ->add('email', 'text', ['label' => __('Email'), 'rules' => "required|string|email|max:255|unique:users"])
            ->add('password', 'password', ['label' => __('Password'), 'rules' => "required|string|min:6"])
            ->add('address', 'text', ['label' => __('Address')])
            ->add('city', 'text', ['label' => __('City')])
            ->add('postal_code', 'text', ['label' => __('Postal Code')])
            ->add('phone', 'text', ['label' => __('Phone'), 'rules' => 'required'])
            ->add('license', 'text', ['label' => __('License')]);

        $this->add('submit', 'submit', ['label' => __('Save')]);
    }
}
