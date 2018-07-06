<?php

namespace App\Forms\Backend\Investor;

use Kris\LaravelFormBuilder\Form;

class EditForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('name', 'text', ['required' => true])
            ->add('slug', 'text', ['required' => true, 'label' => __('Identifier (No spaces)')])
            ->add('submit', 'submit', ['label' => __('Save')]);
    }
}
