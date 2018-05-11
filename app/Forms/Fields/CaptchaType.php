<?php namespace App\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class CaptchaType extends FormField {

    protected function getTemplate()
    {
        return 'forms.fields.captcha';
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        return parent::render($options, $showLabel, $showField, $showError);
    }
}
