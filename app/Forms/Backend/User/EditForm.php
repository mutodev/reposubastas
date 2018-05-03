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
                ->add('source', 'text')
                ->add('type', 'select', [
                    'choices' => [
                        'Broker' => __('Broker'),
                        'Client' => __('Client')
                    ]
                ])
                ->add('broker_name', 'text')
                ->add('martial_status', 'text')
                ->add('spouse_name', 'text')
                ->add('address', 'text')
                ->add('city', 'text')
                ->add('postal_code', 'text')
                ->add('phone', 'text')
                ->add('phone2', 'text')
                ->add('company', 'text')
                ->add('license', 'text')
                ->add('expiration_date', 'date')
                ->add('number', 'number', ['label' => __('Bidder number')])
                ->add('deposit', 'number', ['label' => __('Deposit')]);
        }

        $this->add('submit', 'submit', ['label' => __('Save')]);
    }
}
