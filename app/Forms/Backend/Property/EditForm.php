<?php

namespace App\Forms\Backend\Property;

use Kris\LaravelFormBuilder\Form;
use App\Models\PropertyStatus;
use App\Models\PropertyType;
use App\Models\Investor;

class EditForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('type_id', 'select', [
                'choices' => PropertyType::forSelect(),
                'label' => __('Type'),
                'rules' => 'required'
            ])
            ->add('status_id', 'select', [
                'choices' => PropertyStatus::forSelect(),
                'label' => __('Status')
            ])
            ->add('check_number', 'text')
            ->add('check_type', 'text')
            ->add('bank', 'text')
            ->add('investor_id', 'select', [
                'choices' => Investor::forSelect(),
                'label' => __('Investor')
            ])
            ->add('investor_reference_id', 'text')
            ->add('internal_number', 'text')
            ->add('start_at', 'datetime-local', ['rules' => 'required'])
            ->add('end_at', 'datetime-local', ['rules' => 'required'])
            ->add('number', 'number')
            ->add('address', 'text', ['rules' => 'required'])
            ->add('city', 'text', ['rules' => 'required'])
            ->add('region_es', 'text', ['rules' => 'required', 'label' => __('Region (Spanish)')])
            ->add('region_en', 'text', ['rules' => 'required', 'label' => __('Region (English)')])
            ->add('latitude', 'text', ['rules' => 'required'])
            ->add('longitude', 'text', ['rules' => 'required'])
            ->add('bedrooms', 'number', ['rules' => 'required|numeric'])
            ->add('bathrooms', 'number', ['rules' => 'required|numeric'])
            ->add('price', 'number', ['rules' => 'required|numeric', 'label' => __('Sale Price')])
            //TODO Previous apraisal
            ->add('is_cash_only', 'checkbox')
            ->add('open_house_es', 'text', ['label' => __('Open House (Spanish)')])
            ->add('open_house_en', 'text', ['label' => __('Open House (English)')])
            ->add('zonification_es', 'text', ['label' => __('Zonification (Spanish)')])
            ->add('zonification_en', 'text', ['label' => __('Zonification (English)')])
            //TODO: ANADIR tipo de medido
            ->add('amenities_es', 'text', ['label' => __('Amenities (Spanish)')])
            ->add('amenities_en', 'text', ['label' => __('Amenities (English)')])
            ->add('catastro', 'text')
            ->add('sqf_area', 'text', ['rules' => 'required|numeric'])
            ->add('sqm_area', 'text', ['rules' => 'required|numeric'])
            ->add('cuerdas', 'text', ['rules' => 'required|numeric'])
            ->add('description_es', 'textarea', ['rules' => 'required', 'label' => __('Description (Spanish)')])
            ->add('description_en', 'textarea', ['rules' => 'required', 'label' => __('Description (English)')])
            ->add('images', 'file', ['attr' => ['multiple' => true]])
            ->add('deposit', 'number')
            ->add('reserve', 'number')
            ->add('lister_broker', 'text')
            ->add('seller_broker', 'text')
            ->add('commission', 'number')
            ->add('submit', 'submit', ['label' => __('Save')]);
    }
}
