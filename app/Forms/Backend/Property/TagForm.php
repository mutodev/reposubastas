<?php

namespace App\Forms\Backend\Property;

use Kris\LaravelFormBuilder\Form;

class TagForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('name_es', 'text', ['label' => __('Name (Spanish)')])
            ->add('name_en', 'text', ['label' => __('Name (English)')])
            ->add('submit', 'submit', ['label' => __('Save')]);
    }
}
