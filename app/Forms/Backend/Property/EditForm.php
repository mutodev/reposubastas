<?php

namespace App\Forms\Backend\Property;

use Kris\LaravelFormBuilder\Form;

class EditForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('address', 'text', ['rules' => 'required'])
            ->add('bedrooms', 'number', ['rules' => 'required|numeric'])
            ->add('bathrooms', 'number', ['rules' => 'required|numeric'])
            ->add('price', 'number', ['rules' => 'required|numeric'])
            ->add('open_house', 'text', ['rules' => 'required'])
            ->add('sqf_area', 'text', ['rules' => 'required|numeric'])
            ->add('sqm_area', 'text', ['rules' => 'required|numeric'])
            ->add('internal_number', 'text')
            ->add('latitude', 'text', ['rules' => 'required'])
            ->add('longitude', 'text', ['rules' => 'required'])
            ->add('number', 'number')
            ->add('submit', 'submit', ['label' => __('Save')]);
    }
}
