<?php

namespace App\Forms\Backend\Property;

use Kris\LaravelFormBuilder\Form;
use App\Models\PropertyStatus;
use App\Models\PropertyType;

class EditForm extends Form
{
    public function buildForm()
    {
        $statusChoices = [];
        foreach (PropertyStatus::all() as $status) {
            $statusChoices[$status->id] = $status['name_es'];
        }

        $typeChoices = [];
        foreach (PropertyType::all() as $type) {
            $typeChoices[$type->id] = $type['name_es'];
        }

        $this
            ->add('type_id', 'select', [
                'choices' => $typeChoices,
                'label' => __('Type')
            ])
            ->add('status_id', 'select', [
                'choices' => $statusChoices,
                'label' => __('Type')
            ])
            ->add('address', 'text', ['rules' => 'required'])
            ->add('bedrooms', 'number', ['rules' => 'required|numeric'])
            ->add('bathrooms', 'number', ['rules' => 'required|numeric'])
            ->add('price', 'number', ['rules' => 'required|numeric'])
            ->add('deposit', 'number', ['rules' => 'required|numeric'])
            ->add('open_house', 'text', ['rules' => 'required'])
            ->add('zonification', 'text')
            ->add('roof_height', 'text')
            ->add('lot_size', 'text')
            ->add('levels', 'text')
            ->add('amenities_es', 'text')
            ->add('amenities_en', 'text')
            ->add('catastro', 'text')
            ->add('sqf_area', 'text', ['rules' => 'required|numeric'])
            ->add('sqm_area', 'text', ['rules' => 'required|numeric'])
            ->add('cuerdas', 'text', ['rules' => 'required|numeric'])
            ->add('internal_number', 'text')
            ->add('city', 'text', ['rules' => 'required'])
            ->add('region_es', 'text', ['rules' => 'required'])
            ->add('region_en', 'text', ['rules' => 'required'])
            ->add('latitude', 'text', ['rules' => 'required'])
            ->add('longitude', 'text', ['rules' => 'required'])
            ->add('number', 'number')
            ->add('description_es', 'textarea', ['rules' => 'required'])
            ->add('description_en', 'textarea', ['rules' => 'required'])
            ->add('images', 'file', ['attr' => ['multiple' => true]])
            ->add('submit', 'submit', ['label' => __('Save')]);
    }
}
