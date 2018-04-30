<?php

namespace App\Forms\Backend\User;

use Kris\LaravelFormBuilder\Form;
use Spatie\Permission\Models\Role;

class EditForm extends Form
{
    public function buildForm()
    {
        $roles = Role::all()->toArray();
        $roleOptions = [];
        foreach ($roles as $k => $role) {
            $roleOptions[$role['id']] = $role['name'];
        }

        $model = $this->getModel();
        $role = $model ? current(array_column($model->roles->toArray(), 'id')) : null;

        $event = $this->getRequest()->route()->parameter('event');

        $this
            ->add('name', 'text')
            ->add('email', 'text')
            ->add('password', 'password');

        //Event fields
        if ($event) {
            $this
                ->add('number', 'number')
                ->add('deposit', 'number', ['label' => __('Deposit')]);
        }

        $this->add('submit', 'submit', ['label' => __('Save')]);
    }
}
