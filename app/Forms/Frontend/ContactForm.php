<?php

namespace App\Forms\Frontend;

use Kris\LaravelFormBuilder\Form;

class ContactForm extends Form
{
    public function buildForm()
    {
        $this->add('name', 'text', ['label' => __('Name'), 'rules' => 'required']);
        $this->add('email', 'email', ['label' => __('Email'), 'rules' => 'required']);
        $this->add('phone', 'text', ['label' => __('Phone'), 'rules' => 'required']);
        $this->add('message', 'textarea', ['label' => __('Message'), 'rules' => 'required']);
        $this->add('captcha', 'captcha', ['label' => __('Verification code'), 'rules' => 'required|captcha', 'error_messages' => [
            'captcha.captcha' => __('Invalid')
        ]]);
        $this->add('submit', 'submit', ['label' => __('Send'), 'attr' => [
            'class' => 'form-control bg-light-blue border-0'
        ]]);
    }
}
