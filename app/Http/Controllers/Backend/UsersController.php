<?php

namespace App\Http\Controllers\Backend;

use App\Models\Bid;
use \App\User as Model;
use \App\Models\UserDeposit;
use \App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Forms\Frontend\User\RegisterForm;
use App\Forms\Backend\User\RegisterToEventForm;
use App\Forms\Backend\User\EditForm;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Event $event = null)
    {
        $inEvent = !empty($event->id);

        $models = Model::select('users.*', 'user_event.number', 'user_event.original_deposit', 'user_event.remaining_deposit', 'user_event.is_active as event_is_active')
            ->leftJoin('user_event', 'user_event.user_id', '=', 'users.id');

        if (!$inEvent) {
            $models->role('Admin');
        }

        if ($event) {
            $models->where('user_event.event_id', '=', $event->id);
        }

        $allmodels = new \Illuminate\Database\Eloquent\Collection;;

        if ($keywords = $request->get('keywords')) {
            $keywords = "%{$keywords}%";

            $models->whereRaw('(users.name LIKE ? OR users.email LIKE ? OR users.phone LIKE ? OR users.broker_name LIKE ? OR users.spouse_name LIKE ? OR users.license LIKE ?)', [
                $keywords, $keywords, $keywords, $keywords, $keywords, $keywords
            ]);
        }

        $models = $models->orderBy('user_event.number', 'asc')->paginate(50)->withPath($request->fullUrlWithQuery($request->all()));

        return view('backend.users.index', compact('models', 'allmodels', 'event'));
    }

    public function edit(FormBuilder $formBuilder, Event $event, Model $model = null)
    {
        $inEvent = !empty($event->id);

        if ($model) {
            $model->password = null;

            if ($inEvent) {
                $modelEvent = DB::table('user_event')
                    ->where('user_id', '=', $model->id)
                    ->where('event_id', '=', $event->id)->first();

                if ($modelEvent) {
                    $model->number = $modelEvent->number;
                    $model->deposit = $modelEvent->original_deposit;
                }
            }
        }

        $formClass = $inEvent? RegisterForm::class : EditForm::class;

        $form = $formBuilder->create($formClass, [
            'method' => 'POST',
            'url'    => Model::url('store', @$model->id, @$event->id),
            'model'  => $model
        ], [
            'isBackend' => true,
            'event' => $event
        ]);

        return view('backend.users.edit', compact('form', 'model', 'event'));
    }

    public function store(FormBuilder $formBuilder, Event $event, Model $model = null)
    {
        $inEvent = !empty($event->id);
        $formClass = $inEvent ? RegisterForm::class : EditForm::class;

        $form = $formBuilder->create($formClass, [], [
            'isBackend' => true
        ]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $formValues = $form->getFieldValues();

        if ($formValues['password']) {
            $formValues['password'] = Hash::make($formValues['password']);
        } else {
            unset($formValues['password']);
        }

        if (!$model) {
            $model = new Model;
        }

        $model->fill($formValues);
        $model->save();

        if ($inEvent && @$formValues['number']) {
            $model->addToEvent($event->id, @$formValues['deposit'], $formValues['number']);
        }

        //Admin
        if (!$inEvent) {
            $model->assignRole('Admin');
        }

        return redirect(Model::url('index', null, @$event->id));
    }

    public function assignNumber(Request $request, Event $event = null, Model $model)
    {
        $number = $request->post('number');
        $eventId = $request->post('event_id');

        if (!$number) {
            $userEvent = DB::table('user_event')
                ->where('event_id', $eventId)
                ->orderBy('number', 'desc')
                ->first();

            $number = $userEvent ? $userEvent->number++ : 1;
        } else {
            $userEvent = DB::table('user_event')
                ->where('number', $number)
                ->where('event_id', $eventId)
                ->first();

            if ($userEvent) {
                Session::flash('error', __('Number already in use.'));
                $number = null;
            }
        }

        if ($number) {
            DB::table('user_event')
                ->where('event_id', $eventId)
                ->where('user_id', $model->id)
                ->update(['number' => $number, 'is_active' => true]);

            Session::flash('success', __('Number added to user'));
        }

        return redirect()->back();
    }

    public function registerToEvent(Request $request, FormBuilder $formBuilder, Event $event, Model $model)
    {
        //Handle post
        if ($request->isMethod('post')) {
            $form = $formBuilder->create(RegisterToEventForm::class);

            if (!$form->isValid()) {
                return redirect()->back()->withErrors($form->getErrors())->withInput();
            }

            $formValues = $form->getFieldValues();

            $userEvent = DB::table('user_event')
                ->where('event_id', $event->id)
                ->where('user_id', '!=', $model->id)
                ->where('number', $formValues['number'])->count();

            if ($userEvent) {
                Session::flash('error', __('Bidder number in use!'));
                return redirect(Model::url('register-to-event-post', $model->id, $event->id));
            }

            $model->addToEvent($event->id, 0, $formValues['number']);

            Session::flash('success', __('User added to event!'));
            return redirect(Model::url('index', null, @$event->id));
        }

        $form = $formBuilder->create(RegisterToEventForm::class, [
            'method' => 'POST',
            'url'    => Model::url('register-to-event-post', $model->id, $event->id),
            'model'  => $model
        ]);

        return view('backend.users.register-to-event', compact('form', 'model'));
    }

    public function deposits(Request $request, Event $event = null, Model $model = null)
    {
        //Refund
        if ($depositId = $request->get('deposit_id')) {
            DB::table('user_deposit')
                ->where('id', $depositId)
                ->update(['refunded' => true]);

            Session::flash('success', __('Deposit marked as refunded!'));
            return redirect(Model::url('deposits', @$model->id, @$event->id));
        }

        $Query = UserDeposit::select('user_deposit.*', 'users.name')
            ->leftJoin('users', 'users.id', '=', 'user_deposit.user_id');

        if ($model) {
            $Query->where('user_deposit.user_id', '=', $model->id);
        }

        $models = $Query->orderBy('user_deposit.created_at', 'desc')->get();

        $Query = Bid::select('bid.*', 'users.name', 'properties.*');

        if ($model) {
            $Query->where('bid.user_id', '=', $model->id);
        }

        $bids = $Query->leftJoin('users', 'users.id', '=', 'bid.user_id')
            ->leftJoin('properties', 'properties.id', '=', 'bid.property_id')
            ->orderBy('bid.created_at', 'desc')
            ->get();

        return view('backend.users.deposits', compact('models', 'model', 'bids', 'event'));
    }
}
