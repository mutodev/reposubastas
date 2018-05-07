<?php

namespace App\Forms\Backend\Property;

use Kris\LaravelFormBuilder\Form;
use App\Models\PropertyStatus;
use App\Models\PropertyType;

class EditForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('type_id', 'select', [
                'choices' => PropertyType::forSelect(),
                'label' => __('Type')
            ])
            ->add('status_id', 'select', [
                'choices' => PropertyStatus::forSelect(),
                'label' => __('Status')
            ])
            ->add('address', 'text', ['rules' => 'required'])
            ->add('bedrooms', 'number', ['rules' => 'required|numeric'])
            ->add('bathrooms', 'number', ['rules' => 'required|numeric'])
            ->add('price', 'number', ['rules' => 'required|numeric'])
            ->add('deposit', 'number', ['rules' => 'required|numeric'])
            ->add('reserve', 'number', ['rules' => 'required|numeric'])
            ->add('open_house_es', 'text', ['label' => __('Open House (Spanish)')])
            ->add('open_house_en', 'text', ['label' => __('Open House (English)')])
            ->add('zonification_es', 'text', ['label' => __('Zonification (Spanish)')])
            ->add('zonification_en', 'text', ['label' => __('Zonification (English)')])
            ->add('roof_height', 'text')
            ->add('lot_size', 'text')
            ->add('levels', 'text')
            ->add('amenities_es', 'text', ['label' => __('Amenities (Spanish)')])
            ->add('amenities_en', 'text', ['label' => __('Amenities (English)')])
            ->add('catastro', 'text')
            ->add('sqf_area', 'text', ['rules' => 'required|numeric'])
            ->add('sqm_area', 'text', ['rules' => 'required|numeric'])
            ->add('cuerdas', 'text', ['rules' => 'required|numeric'])
            ->add('internal_number', 'text')
            ->add('city', 'text', ['rules' => 'required'])
            ->add('region_es', 'text', ['rules' => 'required', 'label' => __('Region (Spanish)')])
            ->add('region_en', 'text', ['rules' => 'required', 'label' => __('Region (English)')])
            ->add('latitude', 'text', ['rules' => 'required'])
            ->add('longitude', 'text', ['rules' => 'required'])
            ->add('number', 'number')
            ->add('description_es', 'textarea', ['rules' => 'required', 'label' => __('Description (Spanish)')])
            ->add('description_en', 'textarea', ['rules' => 'required', 'label' => __('Description (English)')])
            ->add('images', 'file', ['attr' => ['multiple' => true]])
            ->add('submit', 'submit', ['label' => __('Save')]);
    }
}
