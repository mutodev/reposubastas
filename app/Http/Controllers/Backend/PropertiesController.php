<?php

namespace App\Http\Controllers\Backend;

use App\Models\Property as Model;
use App\Models\Bid;
use App\Models\Event;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Forms\Backend\Property\EditForm;
use App\Forms\Backend\Property\RegisterToEventForm;
use App\Forms\Backend\Property\BidForm;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class PropertiesController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Event $event)
    {
        $events = \App\Models\Event::where('is_active', 1)->orderBy('created_at', 'desc')->get();

        $models = Model::select('properties.*', 'property_event.number', 'property_event.is_active')
            ->join('property_event', function($join) use ($event) {
                $join->on('property_event.property_id', '=', 'properties.id');
                $join->on('property_event.event_id', '=', DB::raw($event->id));
            })
            ->orderBy('property_event.number', 'asc')->paginate(50)->withPath($request->fullUrlWithQuery($request->all()));

        return view('backend.properties.index', compact('models', 'events', 'event'));
    }

    public function edit(FormBuilder $formBuilder, Event $event, Model $model = null)
    {
        if ($model) {
            $modelEvent = DB::table('property_event')
                ->where('property_id', '=', $model->id)
                ->where('event_id', '=', $event->id)->first();

            if ($modelEvent) {
                $model->number = $modelEvent->number;
            }
        }

        $form = $formBuilder->create(EditForm::class, [
            'method' => 'POST',
            'url'    => route('backend.properties.store', ['event' => $event->id, 'model' => $model ? $model->id : null]),
            'model'  => $model
        ]);

        return view('backend.properties.edit', compact('form', 'model', 'event'));
    }

    public function store(FormBuilder $formBuilder, Event $event, Model $model = null)
    {
        $form = $formBuilder->create(EditForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $formValues = $form->getFieldValues();

        if (!$model) {
            $model = new Model;
        }

        $model->fill($formValues);
        $model->save();
        $model->addToEvent($event->id, $formValues['number']);

        return redirect()->route('backend.properties.index', ['event' => $event->id]);
    }

    public function auction(FormBuilder $formBuilder, Event $event, Model $model)
    {
        $modelEvent = DB::table('property_event')
            ->where('property_id', '=', $model->id)
            ->where('event_id', '=', $event->id)->first();

        $bids = Bid::select('bid.*', 'users.name', 'user_event.number')
            ->where('property_id', '=', $model->id)
            ->where('bid.event_id', '=', $event->id)
            ->join('users', 'users.id', '=', 'bid.user_id')
            ->join('user_event', 'user_event.user_id', '=', 'bid.user_id')
            ->orderBy('bid.created_at', 'desc')->get();

        //Get winner
        $winner = false;
        foreach ($bids as $bid) {
            if ($bid->is_winner) {
                $winner = $bid;
            }
        }

        //Users
        $users = User::select('users.*', 'users.name', 'user_event.number')
            ->where('user_event.event_id', '=', $event->id)
            ->where('user_event.is_active', '=', true)
            ->join('user_event', 'user_event.user_id', '=', 'users.id')->get();

        $form = $formBuilder->create(BidForm::class, [
            'method' => 'POST',
            'class' => 'form-horizontal',
            'url'    => route('backend.properties.bid.store', ['event' => $event->id, 'model' => $model->id]),
        ]);

        return view('backend.properties.auction', compact('form', 'model', 'event', 'modelEvent', 'bids', 'winner', 'users'));
    }

    public function nextAuction(Request $request, Event $event)
    {
        $number = $request->get('number');

        $modelEvent = DB::table('property_event')
            ->where('number', '>', $number)
            ->where('event_id', '=', $event->id)->first();

        if (!$modelEvent) {
            Session::flash('success', __('Theres no more properties in this event'));
            return redirect()->route('backend.properties.index', ['event' => $event->id]);
        }

        return redirect()->route('backend.properties.auction', ['event' => $event->id, 'model' => $modelEvent->property_id]);
    }

    public function bidStore(FormBuilder $formBuilder, Event $event, Model $model)
    {
        $form = $formBuilder->create(BidForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $formValues = $form->getFieldValues();

        $userEvent = DB::table('user_event')
            ->where('number', '=', $formValues['number'])
            ->where('event_id', '=', $event->id)->first();

        if ($userEvent) {
            $bid = new Bid;
            $bid->user_id = $userEvent->user_id;
            $bid->property_id = $model->id;
            $bid->event_id = $event->id;
            $bid->offer = $formValues['offer'];
            $bid->is_winner = false;
            $bid->save();

            Session::flash('success', __('Offer saved successfully!'));
        } else {
            Session::flash('error', __("User with number {$formValues['number']} not found"));
        }

        return redirect()->route('backend.properties.auction', ['event' => $event->id, 'model' => $model->id]);
    }

    public function finishAuction(Event $event, Model $model)
    {
        $bid = $model->endAuction($event->id);

        if ($bid) {
            Session::flash('success', "#{$bid->number} - {$bid->name} is the winner!");
        } else {
            Session::flash('success', "Auction closed without winner!");
        }

        return redirect()->route('backend.properties.auction', ['event' => $event->id, 'model' => $model->id]);
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

            $model->addToEvent($formValues['event_id'], $formValues['number']);

            Session::flash('success', __('Property added to event!'));

            return redirect()->route('backend.properties.index', ['event' => $event->id]);
        }

        $form = $formBuilder->create(RegisterToEventForm::class, [
            'method' => 'POST',
            'url'    => route('backend.properties.register-to-event-post', ['event' => $event->id, 'model' => $model->id]),
            'model'  => $model
        ]);

        return view('backend.users.register-to-event', compact('form', 'model'));
    }

    public function generatePdf(Event $event)
    {
        $pdf = PDF::loadView('backend.properties.pdf', compact('event'));
        return $pdf->download('properties.pdf');
    }
}
