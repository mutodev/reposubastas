<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Bid;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PropertyType;
use View;
use App;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PDF;
use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    public function page(FormBuilder $formBuilder, Request $request, $locale, $pageSlug = null) {
        //dd('dd');
        App::setLocale($locale);
        Date::setLocale($locale);

        if (!in_array($locale, ['es', 'en'])) {
            dd($locale);
        }

        $pageSlug = $pageSlug ?: 'homepage';
        $page = Page::where('slug_es', '=', $pageSlug)->orWhere('slug_en', '=', $pageSlug)->first();
        $data = ['page' => $page];

        if (method_exists($this, $pageSlug)) {
            $response = $this->{$pageSlug}($formBuilder, $request);

            if (is_array($response)) {
                $data = array_merge($data, $response);
            } else {
                return $response;
            }
        }

        $view = $page ? $page->slug_en : $pageSlug;

        if (!view()->exists("frontend.{$view}")) {
            $view = "default";
        }

        return view("frontend.{$view}", $data);
    }

    public function homepage(FormBuilder $formBuilder, $request) {
        $types = PropertyType::forSelect();

        $event = App\Models\Event::orderBy('created_at', 'desc')->first();

        return compact('types', 'event');
    }

    public function properties(FormBuilder $formBuilder, Request $request) {

        $today = date('Y-m-d H:i:s');

        $query = Property::select('properties.*', 'property_event.number', 'events.start_at as event_start_at', 'events.end_at as event_end_at', 'events.live_at as event_live_at', 'events.location as event_location')
            ->join('property_event', function($join) {
                $join->on('property_event.property_id', '=', 'properties.id')
                    ->where('property_event.is_active', '=', true);
            })
            ->join('events', function($join) use ($today) {
                $join->on('events.id', '=', 'property_event.event_id')
                    ->where('events.is_active', '=', true);
            })
            ->where('properties.start_at', '<=', $today)
            ->where('properties.end_at', '>', $today);

        if ($type = $request->get('type')) {
            $query->where('properties.type_id', '=', $type);
        }

        if ($event = $request->get('event')) {
            $query->where('property_event.event_id', '=', $event);
        }

        if ($eventType = $request->get('event_type')) {
            if ($eventType == 'LIVE') {
                $query->whereNotNull('property_event.number');
            } elseif ($eventType == 'ONLINE') {
                $query->whereNull('property_event.number');
            }
        }

        if ($priceMin = $request->get('price_min')) {
            $query->where('properties.price', '>=', $priceMin);
        }

        if ($priceMax = $request->get('price_max')) {
            $query->where('properties.price', '<=', $priceMax);
        }

        if ($keywords = $request->get('keywords')) {
            $keywordsLike = "%{$keywords}%";

            if (is_numeric($keywords)) {
                $query->whereRaw('property_event.number = ?', [
                    $keywords
                ]);
            } else {
                $query->whereRaw('(properties.address LIKE ? or properties.city LIKE ? or properties.region_es LIKE ? or properties.region_en LIKE ?)', [
                    $keywordsLike,
                    $keywordsLike,
                    $keywordsLike,
                    $keywordsLike
                ]);
            }
        }

        if ($request->get('pdf')) {
            $propertiesByCity = (clone $query)->orderBy('properties.city', 'asc')->get();
            $propertiesByNumber = (clone $query)->orderBy('property_event.number', 'asc')->get();

            set_time_limit(-1);
            $pdf = PDF::loadView('frontend.pdf', compact('propertiesByCity', 'propertiesByNumber'))->setPaper('Letter');
            return $pdf->download('properties.pdf');
        }

        $properties = $query->orderBy('property_event.number')->paginate(9);

        $types = PropertyType::forSelect();

        return compact('types', 'properties');
    }

    public function property(FormBuilder $formBuilder, $request) {

        $today = date('Y-m-d H:i:s');

        $property = Property::select('properties.*', 'property_event.number', 'events.start_at as event_start_at', 'events.live_at as event_live_at', 'events.end_at as event_end_at', 'events.id as event_id', 'events.location as event_location')
            ->join('property_event', function($join) {
                $join->on('property_event.property_id', '=', 'properties.id')
                    ->where('property_event.is_active', '=', true);
            })
            ->join('events', function($join) use ($today) {
                $join->on('events.id', '=', 'property_event.event_id')
                    ->where('events.is_active', '=', true);
            })
            ->where('properties.start_at', '<=', $today)
            ->where('properties.end_at', '>', $today)
            ->where('properties.id', '=', $request->get('id'))->first();

        //Get last bid
        $bid = $property->getBids($property->event_id)->first();

        //Handle post
        if ($request->isMethod('post')) {
            if (\Auth::guest()) {
                Session::flash('error', __('Please register to submit offer'));
                return redirect()->route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'register']);
            }

            $form = $formBuilder->create(App\Forms\Frontend\Property\OfferForm::class);

            if (!$form->isValid()) {
                return redirect()->back()->withErrors($form->getErrors())->withInput();
            }

            $formValues = $form->getFieldValues();

            $newOffer = intval($formValues['offer']);

            if ($newOffer >= $property->reserve && (!$bid || $newOffer > intval($bid->offer))) {
                $bid = new Bid;
                $bid->user_id = \Auth::user()->id;
                $bid->property_id = $property->id;
                $bid->event_id = $property->event_id;
                $bid->offer = intval($formValues['offer']);
                $bid->is_winner = false;
                $bid->save();

                \Auth::user()->addToEvent($property->event_id, 0);

                Session::flash('success', __('Offer submitted'));
            } else {
                Session::flash('error', __('The offer must be greater than actual offer'));
            }
        }

        $form = $formBuilder->create(App\Forms\Frontend\Property\OfferForm::class, [
            'method' => 'POST',
            'url'    => route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'property', 'id' => $property->id]),
            'model'  => [
                'offer' => intval($bid->offer ?? $property->price)
            ]
        ]);

        $types = PropertyType::forSelect();
        $online = empty($property->number);

        return compact('types', 'property', 'online', 'form', 'bid');
    }

    public function register($formBuilder, $request) {

        //Handle post
        if ($request->isMethod('post')) {
            $form = $formBuilder->create(App\Forms\Frontend\User\RegisterForm::class);

            if (!$form->isValid()) {
                return redirect()->back()->withErrors($form->getErrors())->withInput();
            }

            $formValues = $form->getFieldValues();

            if ($formValues['password']) {
                $formValues['password'] = Hash::make($formValues['password']);
            } else {
                unset($formValues['password']);
            }

            $user = new App\User;
            $user->fill($formValues);
            $user->save();

            \Auth::login($user);

            Session::flash('success', __('Thanks for registering'));

            return redirect()->route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'properties']);
        }

        $form = $formBuilder->create(App\Forms\Frontend\User\RegisterForm::class, [
            'method' => 'POST',
            'url'    => route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'register'])
        ]);

        return compact('form');
    }

    public function login($formBuilder, $request) {

        //Handle post
        if ($request->isMethod('post')) {
            $form = $formBuilder->create(App\Forms\Frontend\User\LoginForm::class);

            if (!$form->isValid()) {
                return redirect()->back()->withErrors($form->getErrors())->withInput();
            }

            if (\Auth::attempt($form->getFieldValues())) {
                // Authentication passed...
                return redirect()->intended(route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'properties']));
            }

            Session::flash('error', __('Invalid credentials'));
        }

        $form = $formBuilder->create(App\Forms\Frontend\User\LoginForm::class, [
            'method' => 'POST',
            'url'    => route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'login'])
        ]);

        return compact('form');
    }

    public function contact($formBuilder, $request) {

        //Handle post
        if ($request->isMethod('post')) {
            $form = $formBuilder->create(App\Forms\Frontend\ContactForm::class);

            if (!$form->isValid()) {
                return redirect()->back()->withErrors($form->getErrors())->withInput();
            }

            Mail::to(env('CONTACT_EMAIL'))->send(new Contact($form->getFieldValues()));

            Session::flash('success', __('Thank you for contacting us'));

            return redirect()->route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'contact']);
        }

        $form = $formBuilder->create(App\Forms\Frontend\ContactForm::class, [
            'method' => 'POST',
            'url'    => route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'contact'])
        ]);

        return compact('form');
    }
}
