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
        $event = $this->getData('event');

        $users = $models = \App\User::select('users.*', 'user_event.number')
        ->leftJoin('user_event', function ($join) use ($event) {
            $join->on('user_event.user_id', '=', 'users.id');
            $join->on('user_event.event_id', '=', \DB::raw($event->id));
        })
        ->orderBy('user_event.number');

        $usersArray = [];
        foreach ($users->get() as $user) {
            $usersArray[$user->name . ' (#'.$user->id.')'] = $user->id;
        }

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
            ->add('sold_closing_at', 'date')
            ->add('cancel_reason', 'text', ['label' => __('Cancel Reason')])
            ->add('optioned_by', 'datalist', [
                'label' => __('Optioned By'),
                'style' => 'width:200px',
                'empty_value' => '-- Select One --',
                //'attr' => ['data-data' => ($this->model && $this->model->optioned_by) ? htmlspecialchars(json_encode(['id' => $this->model->optioned_by, 'name' => $this->model->optionedUser->name, 'phone' => $this->model->optionedUser->phone])) : ''],
                'selected' => ($this->model && $this->model->optioned_by) ? $this->model->optioned_by : null,
                'choices' => $usersArray
            ])
            ->add('optioned_approved_at', 'date')
            ->add('user_number', 'text')
            ->add('optioned_end_at', 'date')
            ->add('optioned_price', 'number', ['label' => __('Approved Sale Price')])
            ->add('check_number', 'text')
            ->add('check_type', 'text')
            ->add('check_amount', 'number', ['label' => __('Optioned Deposit Amount')])
            ->add('bank', 'text')
            ->add('optioned_method', 'select', [
                'choices' => [
                    'FINANCED' => 'Financed',
                    'CASH'     => 'Cash'
                ],
                'empty_value' => '-- Select One --',
            ])
            ->add('financing_bank', 'text')
            ->add('financing_phone', 'text')
            ->add('financing_contact', 'text')
            ->add('investor_id', 'select', [
                'choices' => Investor::forSelect(),
                'label' => __('Investor')
            ])
            ->add('investor_reference_id', 'text')
            ->add('internal_number', 'text')
            ->add('starting_bid', 'number', ['label' => __('Starting Bid')])
            ->add('bidding_start_at', 'datetime-local', ['rules' => 'required'])
            ->add('start_at', 'datetime-local', ['rules' => 'required'])
            ->add('end_at', 'datetime-local', ['rules' => 'required'])
            ->add('number', 'number', ['label' => __('Catalog Number')])
            ->add('address', 'text', ['rules' => 'required'])
            ->add('city', 'text', ['rules' => 'required'])
            ->add('region_es', 'text', ['rules' => 'required', 'label' => __('Region (Spanish)')])
            ->add('region_en', 'text', ['rules' => 'required', 'label' => __('Region (English)')])
            ->add('latitude', 'text')
            ->add('longitude', 'text')
            ->add('catastro', 'text')
            ->add('zonification_es', 'text', ['label' => __('Zoning (Spanish)')])
            ->add('zonification_en', 'text', ['label' => __('Zoning (English)')])
            ->add('price', 'number', ['rules' => 'numeric', 'label' => __('Sale Price')])
            ->add('buyer_prima', 'number', ['rules' => 'numeric', 'label' => __('Buyer Prima')])
            //TODO Previous apraisal
            ->add('is_cash_only', 'checkbox', ['label' => __('Cash only')])
            ->add('bedrooms', 'number')
            ->add('bathrooms', 'text')
            ->add('sqm_area', 'text')
            ->add('cuerdas', 'text')
            ->add('sqf_area', 'text')
            //TODO: ANADIR tipo de medido
            ->add('amenities_es', 'text', ['label' => __('Amenities (Spanish)')])
            ->add('amenities_en', 'text', ['label' => __('Amenities (English)')])
            ->add('description_es', 'textarea', ['rules' => 'required', 'label' => __('Description (Spanish)')])
            ->add('description_en', 'textarea', ['rules' => 'required', 'label' => __('Description (English)')])
            ->add('open_house_es', 'text', ['label' => __('Open House (Spanish)')])
            ->add('open_house_en', 'text', ['label' => __('Open House (English)')])
            //->add('images', 'file', ['attr' => ['multiple' => true]])
            ->add('deposit', 'number')
            ->add('reserve', 'number')
            ->add('lister_broker', 'text')
            ->add('seller_broker', 'text')
            ->add('commission', 'number', ['label' => __('Commission (%)')])
            ->add('comments', 'textarea')
            ->add('youtube_video', 'text')
            ->add('notes_original_loan_amount', 'number')
            ->add('notes_position', 'text')
            ->add('notes_current_balance', 'number')
            ->add('notes_loan_type', 'text')
            ->add('notes_interest_rate', 'number')
            ->add('notes_term', 'text')
            ->add('notes_monthly_payment', 'number')
            ->add('notes_date_of_origination', 'text')
            ->add('notes_title_report', 'text')
            ->add('notes_lien_judgements_against_property', 'text')
            ->add('notes_mortgage_note', 'text')
            ->add('notes_deed', 'text')
            ->add('notes_crim', 'text', ['label' => __('Notes CRIM')])
            ->add('notes_annual_payment', 'number')
            ->add('notes_hoa', 'text', ['label' => __('Notes HOA')])
            ->add('notes_ley_seven', 'text', ['label' => __('Notes LEY 7')])
            ->add('notes_crim_debt', 'text', ['label' => __('Notes CRIM debt')])
            ->add('notes_past_due_hoa', 'text', ['label' => __('Notes past due HOA')])
            ->add('submit', 'submit', ['label' => __('Save')]);
    }
}
