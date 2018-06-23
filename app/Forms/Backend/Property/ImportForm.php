<?php

namespace App\Forms\Backend\Property;

use Kris\LaravelFormBuilder\Form;

class ImportForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('csv', 'file')
            ->add('submit', 'submit', ['label' => __('Save')]);
    }
}
