<?php

namespace App\Forms\Frontend\User;

use Kris\LaravelFormBuilder\Form;

class LoginForm extends Form
{
    protected $formOptions = [
        'autocomplete' => 'off',
    ];

    public function buildForm()
    {
        $this
            ->add('email', 'text', ['label' => __('Email'), 'rules' => 'required|string|email'])
            ->add('password', 'password', ['label' => __('Password'), 'rules' => 'required|string']);

        $this->add('submit', 'submit', ['label' => __('Sign In')]);
    }
}
