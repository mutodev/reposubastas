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
use URL;
use Storage;
use App;

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

        $query = Model::select('properties.*', 'property_event.number', 'property_event.is_active')
            ->join('property_event', function($join) use ($event) {
                $join->on('property_event.property_id', '=', 'properties.id');
                $join->on('property_event.event_id', '=', DB::raw($event->id));
            });

        if ($status = $request->get('status')) {
            $query->where('properties.status_id', '=', $status);
        }

        if ($investor = $request->get('investor')) {
            $query->where('properties.investor_id', '=', $investor);
        }

        if ($keywords = $request->get('keywords')) {
            $keywords = "%{$keywords}%";

            $query->whereRaw('(properties.investor_reference_id LIKE ? or properties.address LIKE ? or properties.city LIKE ? or properties.region_es LIKE ? or properties.region_en LIKE ? or property_event.number LIKE ?)', [
                $keywords,
                $keywords,
                $keywords,
                $keywords,
                $keywords,
                $keywords
            ]);
        }

        $models = $query->orderBy('property_event.number', 'asc')->paginate(50)->withPath($request->fullUrlWithQuery($request->all()));

        return view('backend.properties.index', compact('models', 'events', 'event'));
    }

    public function edit(FormBuilder $formBuilder, Event $event, Model $model = null)
    {
        $startAt = $event->start_at;
        $endAt = $event->end_at;

        if ($model) {
            $modelEvent = DB::table('property_event')
                ->where('property_id', '=', $model->id)
                ->where('event_id', '=', $event->id)->first();

            if ($modelEvent) {
                $model->number = $modelEvent->number;
            }

            $startAt = $model->start_at;
            $endAt =$model->end_at;
        }

        if (!$model) {
            $model = new Model;
        }

        $model->start_at = date("Y-m-d\TH:i:s", strtotime($startAt));
        $model->end_at = date("Y-m-d\TH:i:s", strtotime($endAt));

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

        foreach ((array)$formValues['images'] as $i => $image) {
            $index = $i+1;
            $imageFilename = uniqid("{$index}_").'.jpg';

            if (Storage::disk('s3')->put($imageFilename, file_get_contents($image), 'public')) {
                $formValues["image{$index}"] = $imageFilename;
            }
        }

        $formValues['start_at'] = date('Y-m-d H:i:s', strtotime($formValues['start_at']));
        $formValues['end_at'] = date('Y-m-d H:i:s', strtotime($formValues['end_at']));

        $model->fill($formValues);
        $model->save();
        $model->addToEvent($event->id, $formValues['number']);

        return redirect()->route('backend.properties.index', ['event' => $event->id]);
    }

    public function auction(Request $request, FormBuilder $formBuilder, Event $event, Model $model)
    {
        $modelEvent = $model->getEventData($event->id);
        $bids = $model->getBids($event->id);

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

        //Broadcast
        if (!$request->has('bidding')) {
            event(new \App\Events\Auction($model, $modelEvent));
        }

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
            ->leftJoin('users', 'users.id', '=', 'user_event.user_id')
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

            //Broadcast
            event(new \App\Events\Bid($bid, $userEvent));

            Session::flash('success', __('Offer saved successfully!'));
        } else {
            Session::flash('error', __("User with number {$formValues['number']} not found"));
        }

        return redirect()->route('backend.properties.auction', ['event' => $event->id, 'model' => $model->id, 'bidding' => true]);
    }

    public function finishAuction(Request $request, Event $event, Model $model)
    {
        $bid = $model->endAuction($event->id, $request->get('status_id'));

        if ($bid) {
            Session::flash('success', "#{$bid->number} - {$bid->name} is the winner!");

            //Broadcast
            event(new \App\Events\Bid($bid));
        } else {
            Session::flash('success', "Auction closed without winner!");
        }

        return redirect()->route('backend.properties.auction', ['event' => $event->id, 'model' => $model->id, 'bidding' => true]);
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

    public function generatePdf(Event $event, $locale = 'es')
    {
        App::setLocale($locale);

        $baseQuery = Model::select('properties.*', 'property_event.number', 'property_event.is_active')
            ->join('property_event', function($join) use ($event) {
                $join->on('property_event.property_id', '=', 'properties.id');
                $join->on('property_event.event_id', '=', DB::raw($event->id));
            })
            ->where('property_event.is_active', '=', 1);

        $propertiesByCity = (clone $baseQuery)->orderBy('properties.city', 'asc')->get();
        $propertiesByNumber = (clone $baseQuery)->orderBy('property_event.number', 'asc')->get();

        set_time_limit(-1);
        $pdf = PDF::loadView('frontend.pdf', compact('event', 'propertiesByCity', 'propertiesByNumber'))->setPaper('half-letter');
        return $pdf->download('properties.pdf');
    }
}
