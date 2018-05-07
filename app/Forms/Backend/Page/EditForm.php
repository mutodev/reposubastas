<?php

namespace App\Forms\Backend\Page;

use Kris\LaravelFormBuilder\Form;
use App\Models\PropertyStatus;
use App\Models\PropertyType;

class EditForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('title_es', 'text', ['label' => __('Title (Spanish)')])
            ->add('title_en', 'text', ['label' => __('Title (English)')])
            ->add('content_es', 'textarea', ['required' => true, 'label' => __('Content (Spanish)')])
            ->add('content_en', 'textarea', ['required' => true, 'label' => __('Content (English)')])
            ->add('slug_es', 'text', ['label' => __('URL (Spanish)')])
            ->add('slug_en', 'text', ['label' => __('URL (English)')])
            ->add('submit', 'submit', ['label' => __('Save')]);
    }
}
