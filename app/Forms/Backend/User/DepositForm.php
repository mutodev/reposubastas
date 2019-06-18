<?php

namespace App\Forms\Backend\User;

use Kris\LaravelFormBuilder\Form;

class DepositForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('amount', 'number', ['rules' => 'required'])
            ->add('submit', 'submit', ['label' => __('Save')]);
    }
}
