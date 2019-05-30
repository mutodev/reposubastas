<?php

namespace App\Http\Controllers;

use App;
use App\Mail\Contact;
use App\Models\Bid;
use App\Models\Page;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Jenssegers\Date\Date;
use Kris\LaravelFormBuilder\FormBuilder;
use PDF;
use View;

class FrontendController extends Controller
{
    public function page(FormBuilder $formBuilder, Request $request, $locale, $pageSlug = null)
    {
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

    public function homepage(FormBuilder $formBuilder, $request)
    {
        $types = PropertyType::forSelect();

        $event = App\Models\Event::orderBy('created_at', 'desc')->first();

        return compact('types', 'event');
    }

    public function properties(FormBuilder $formBuilder, Request $request)
    {
        $today = date('Y-m-d H:i:s');

        $query = Property::select('properties.*', 'property_event.number', 'events.id as event_id', 'events.is_online as event_is_online', 'events.start_at as event_start_at', 'events.end_at as event_end_at', 'events.live_at as event_live_at', 'events.location as event_location')
            ->join('property_event', function ($join) {
                $join->on('property_event.property_id', '=', 'properties.id')
                    ->where('property_event.is_active', '=', true);
            })
            ->join('events', function ($join) use ($today) {
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

        if ($id = $request->get('id')) {
            $query->whereIn('properties.id', $id);
        }

        if ($request->get('pdftest')) {
            if ($request->get('admin')) {
                $query->with('investor');
            }

            $total = (clone $query)->count();
            $propertiesByCity = (clone $query)->orderBy('properties.city', 'asc');
            $propertiesByNumber = (clone $query)->orderBy('property_event.number', 'asc');

            set_time_limit(-1);
            return view('frontend.pdf', compact('propertiesByCity', 'propertiesByNumber', 'total'));
        }

        if ($request->get('pdf')) {
            $total = (clone $query)->count();
            $propertiesByCity = (clone $query)->orderBy('properties.city', 'asc');
            $propertiesByNumber = (clone $query)->orderBy('property_event.number', 'asc');

            set_time_limit(-1);
            $pdf = \DomPDF::loadView('frontend.pdf', compact('propertiesByCity', 'propertiesByNumber', 'total'))->setPaper('half-letter');
            return $pdf->download('properties.pdf');
        }

        $properties = $query->orderBy('property_event.number')->paginate(9);

        $types = PropertyType::forSelect();

        return compact('types', 'properties');
    }

    public function bulk(FormBuilder $formBuilder, Request $request)
    {

        $today = date('Y-m-d H:i:s');

        $query = Property::select('properties.*', 'property_event.number', 'events.id as event_id', 'events.start_at as event_start_at', 'events.end_at as event_end_at', 'events.live_at as event_live_at', 'events.location as event_location')
            ->join('property_event', function ($join) {
                $join->on('property_event.property_id', '=', 'properties.id')
                    ->where('property_event.is_active', '=', true);
            })
            ->leftJoin('property_tag_pivot', function ($join) {
                $join->on('property_tag_pivot.property_id', '=', 'properties.id');
            })
            ->leftJoin('property_tag', function ($join) use ($today) {
                $join->on('property_tag.id', '=', 'property_tag_pivot.property_tag_id');
            })
            ->join('events', function ($join) use ($today) {
                $join->on('events.id', '=', 'property_event.event_id')
                    ->where('events.is_active', '=', true);
            })
            ->where('properties.start_at', '<=', $today)
            ->where('properties.end_at', '>', $today);

        $ids = session()->get('selected', []);

        $query->whereIn('properties.id', $ids);

        $properties = $query->orderBy('property_event.number')->paginate(100);

        //Handle post
        if ($request->isMethod('post')) {
            $form = $formBuilder->create(App\Forms\Frontend\Property\BulkForm::class);

            if (!$form->isValid()) {
                return redirect()->back()->withErrors($form->getErrors())->withInput();
            }

            if (count($properties) < 2) {
                Session::flash('error', __('There is a minimum limit of 2 properties'));
                return redirect()->route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'bulk'])->withInput();
            }

            if (count($properties) > 5) {
                Session::flash('error', __('There is a maximum limit of 5 properties'));
                return redirect()->route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'bulk'])->withInput();
            }

            $email = $form->getFieldValues();
            foreach ($properties as $k => $property) {
                $email['Property ' . ($k + 1)] = $property->address . ' ' . $property->city;
            }
            $email['offer'] = '$' . number_format($email['offer']);

            Mail::to(explode(',', env('CONTACT_EMAIL')))->send(new Contact('REPOSUBASTA - Bulk Offer', $email));

            Session::flash('success', __('Offer sent! We will reply as soon as possible'));

            session()->set('selected', []);

            return redirect()->route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'bulk']);
        }

        $form = $formBuilder->create(App\Forms\Frontend\Property\BulkForm::class, [
            'method' => 'POST',
            'url' => route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'bulk']),
            'model'  => \Auth::user()
        ]);

        return compact('properties', 'form');
    }

    public function property(FormBuilder $formBuilder, $request)
    {
        $id = $request->get('id');

        if ($request->ajax() && empty($request->get('transactions'))) {
            $clear = $request->get('clear');

            if ($clear) {
                \Session::put('selected', []);
            } else {
                $selected = session()->pull('selected', []);

                if (($key = array_search($id, $selected)) !== false) {
                    unset($selected[$key]);
                } else {
                    $selected[] = $id;
                }

                \Session::put('selected', $selected);
            }

            return;
        }

        $today = date('Y-m-d H:i:s');

        $property = Property::select('properties.*', 'property_event.number', 'events.is_online as event_is_online', 'events.start_at as event_start_at', 'events.live_at as event_live_at', 'events.end_at as event_end_at', 'events.id as event_id', 'events.location as event_location')
            ->join('property_event', function ($join) {
                $join->on('property_event.property_id', '=', 'properties.id')
                    ->where('property_event.is_active', '=', true);
            })
            ->join('events', function ($join) use ($today) {
                $join->on('events.id', '=', 'property_event.event_id')
                    ->where('events.is_active', '=', true);
            })
            ->where('properties.start_at', '<=', $today)
            ->where('properties.end_at', '>', $today)
            ->where('properties.id', '=', $request->get('id'))->first();

        //Get last bid
        $bid = $property->getBids($property->event_id)->first();

        $userEvent = !\Auth::guest() ? App\Models\UserEvent::query()->where('user_id', \Auth::user()->id)->where('event_id', $property->event_id)->where('is_active', true)->first() : null;

        //Handle post
        if ($request->isMethod('post')) {
            if (\Auth::guest()) {
                Session::flash('error', __('Please register to submit offer'));
                return redirect()->route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'register']);
            }

            if ($request->ajax()) {
                //Deposit log
                $UserDeposit = new App\Models\UserDeposit();
                $UserDeposit->fill([
                    'amount' => @$request->get('transactions')[0]['amount']['total'],
                    'user_id' => \Auth::user()->i
                ]);
                $UserDeposit->save();

                if (!$userEvent) {
                    $userEvent = new App\Models\UserEvent();
                }

                $userEvent->fill([
                    'remaining_deposit' => @$request->get('transactions')[0]['amount']['total'],
                    'user_id' => \Auth::user()->id,
                    'event_id' => $property->event_id,
                    'is_active' => true
                ]);
                $userEvent->save();
                die('done');
            }

            $userDeposit = App\Models\UserDeposit::whereRaw('user_deposit.refunded != 1 AND user_deposit.user_id = ? AND (user_deposit.property_id = ? OR user_deposit.property_id IS NULL)', [\Auth::user()->id, $property->id])->first();

            if (!$userDeposit) {
                Session::flash('error', __('You must present your purchase intention by processing a minimum deposit') . '<paypal
                                            amount="1575.00"
                                            currency="USD"
                                            :client="credentials"
                                            v-on:payment-authorized="paymentAuthorized"
                                            v-on:payment-completed="paymentCompleted"
                                            v-on:payment-cancelled="paymentCancelled"
                                    ></paypal>');
            } else {
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
                    $bid->type = $formValues['type'];
                    $bid->is_winner = false;
                    $bid->save();

                    $formValues['property_number'] = $property->id;
                    $formValues['user'] = \Auth::user()->name;

                    //Attach deposit to property
                    $userDeposit->property_id = $property->id;
                    $userDeposit->save();

                    Mail::to(explode(',', env('CONTACT_EMAIL')))->send(new Contact('REPOSUBASTA - Offer', $formValues));

                    Session::flash('success', __('Offer submitted'));
                } else {
                    Session::flash('error', __('The offer must be greater than actual offer'));
                    $bid = new Bid;
                    $bid->user_id = \Auth::user()->id;
                    $bid->property_id = $property->id;
                    $bid->event_id = $property->event_id;
                    $bid->offer = intval($formValues['offer']);
                    $bid->type = $formValues['type'];
                    $bid->is_winner = false;
                    $bid->save();
                }
            }
        }

        $form = $formBuilder->create(App\Forms\Frontend\Property\OfferForm::class, [
            'method' => 'POST',
            'url' => route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'property', 'id' => $property->id]),
            'model' => [
                'offer' => intval($bid->offer ?? $property->price)
            ]
        ]);

        $types = PropertyType::forSelect();
        $online = $property->event_is_online;

        return compact('types', 'property', 'online', 'form', 'bid', 'userEvent');
    }

    public function register($formBuilder, $request)
    {

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

            //Add user to current event
            $event = App\Models\Event::orderBy('created_at', 'desc')->first();
            $user->addToEvent($event->id);

            \Auth::login($user);

            Mail::to(explode(',', env('CONTACT_EMAIL')))->send(new Contact('REPOSUBASTA - Register', $formValues));

            Session::flash('success', __('Thanks for registering'));

            return redirect()->route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'register-success']);
        }

        $form = $formBuilder->create(App\Forms\Frontend\User\RegisterForm::class, [
            'method' => 'POST',
            'url' => route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'register'])
        ]);

        return compact('form');
    }

    public function login($formBuilder, $request)
    {

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
            'url' => route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'login'])
        ]);

        return compact('form');
    }

    public function contact($formBuilder, $request)
    {

        //Handle post
        if ($request->isMethod('post')) {
            $form = $formBuilder->create(App\Forms\Frontend\ContactForm::class);

            if (!$form->isValid()) {
                return redirect()->back()->withErrors($form->getErrors())->withInput();
            }

            Mail::to(explode(',', env('CONTACT_EMAIL')))->send(new Contact('REPOSUBASTA - Contact', $form->getFieldValues()));

            Session::flash('success', __('Thank you for contacting us'));

            return redirect()->route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'contact']);
        }

        $form = $formBuilder->create(App\Forms\Frontend\ContactForm::class, [
            'method' => 'POST',
            'url' => route('frontend.page', ['local' => App::getLocale(), 'pageSlug' => 'contact'])
        ]);

        return compact('form');
    }
}
