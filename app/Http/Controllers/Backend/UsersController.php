<?php

namespace App\Http\Controllers\Backend;

use \App\User as Model;
use \App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Forms\Backend\User\EditForm;
use App\Forms\Backend\User\RegisterToEventForm;
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
        $models = Model::select('users.*', 'user_event.number', 'user_event.original_deposit', 'user_event.remaining_deposit', 'user_event.is_active as event_is_active')
            ->leftJoin('user_event', 'user_event.user_id', '=', 'users.id');

        if ($event) {
            $models->where('user_event.event_id', '=', $event->id);
        }

        $models = $models->orderBy('user_event.number', 'asc')->paginate(50)->withPath($request->fullUrlWithQuery($request->all()));

        return view('backend.users.index', compact('models', 'event'));
    }

    public function edit(FormBuilder $formBuilder, Event $event, Model $model = null)
    {
        if ($model) {
            $model->password = null;

            if ($event) {
                $modelEvent = DB::table('user_event')
                    ->where('user_id', '=', $model->id)
                    ->where('event_id', '=', $event->id)->first();

                if ($modelEvent) {
                    $model->number = $modelEvent->number;
                    $model->deposit = $modelEvent->original_deposit;
                }
            }
        }

        $form = $formBuilder->create(EditForm::class, [
            'method' => 'POST',
            'url'    => Model::url('store', @$model->id, @$event->id),
            'model'  => $model
        ]);

        return view('backend.users.edit', compact('form', 'model', 'event'));
    }

    public function store(FormBuilder $formBuilder, Event $event = null, Model $model = null)
    {
        $form = $formBuilder->create(EditForm::class);

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

        $model->syncRoles(['Bidder']);

        if ($event) {
            $model->addToEvent($event->id, $formValues['deposit'], $formValues['number']);
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

    public function registerToEvent(Request $request, FormBuilder $formBuilder, Model $model)
    {
        //Handle post
        if ($request->isMethod('post')) {
            $form = $formBuilder->create(RegisterToEventForm::class);

            if (!$form->isValid()) {
                return redirect()->back()->withErrors($form->getErrors())->withInput();
            }

            $formValues = $form->getFieldValues();

            $model->addToEvent($formValues['event_id'], 0, $formValues['number']);

            Session::flash('success', __('User added to event!'));

            return redirect()->route('backend.users.index');
        }

        $form = $formBuilder->create(RegisterToEventForm::class, [
            'method' => 'POST',
            'url'    => Model::url('register-to-event-post', $model->id),
            'model'  => $model
        ]);

        return view('backend.users.register-to-event', compact('form', 'model'));
    }
}
