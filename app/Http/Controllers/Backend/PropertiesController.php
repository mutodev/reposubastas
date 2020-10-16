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
use App\Forms\Backend\Property\AddTagForm;
use App\Forms\Backend\Property\BidForm;
use App\Forms\Backend\Property\ImportForm;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDFSnappy;
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

        if ($type = $request->get('type')) {
            $query->where('properties.type_id', '=', $type);
        }

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

        $properties = Model::select('properties.*', 'property_event.number', 'property_event.is_active')
            ->join('property_event', function($join) use ($event) {
                $join->on('property_event.property_id', '=', 'properties.id');
                $join->on('property_event.event_id', '=', DB::raw($event->id));
            })
            ->whereNotNull('properties.status_id')
            ->get();

        $byStatus = [];
        foreach ($properties as $property) {
            $sum = @$byStatus[$property->status->name_en]['sum'];


            if ($property->optioned_price) {
                $sum += $property->optioned_price;
            }

            $byStatus[$property->status->name_en] = [
                'total' => @$byStatus[$property->status->name_en]['total'] + 1,
                'sum'   => $sum,
            ];
        }

        $bids = \App\Models\Bid::with(['property.status' => function ($query) {
                $query->whereIn('property_status.slug', ['APPROVED', 'OPTIONED', 'SOLD']);
            }])
            ->where('event_id', '=', $event->id)->orderBy('bid.offer', 'desc')->get()->unique('property_id')->toArray();

        $bidsTotal = 0;
        foreach ($bids as $bid) {
            $bidsTotal += (float)$bid['offer'];
        }

        $selected = \Session::get('selected', []);

        return view('backend.properties.index', compact('models', 'events', 'event', 'bidsTotal', 'byStatus', 'selected'));
    }

    public function edit(FormBuilder $formBuilder, Event $event, Model $model = null)
    {
        $startAt = $event->start_at;
        $endAt = $event->end_at;
        $biddingStartAt = $event->bidding_start_at;

        if ($model) {
            $modelEvent = DB::table('property_event')
                ->where('property_id', '=', $model->id)
                ->where('event_id', '=', $event->id)->first();

            if ($modelEvent) {
                $model->number = $modelEvent->number;
            }

            $startAt = $model->start_at;
            $endAt =$model->end_at;
            $biddingStartAt =$model->bidding_start_at;

            if ($model->sold_closing_at) {
                $model->sold_closing_at = date("Y-m-d", strtotime($model->sold_closing_at));
            }

            if ($model->optioned_approved_at) {
                $model->optioned_approved_at = date("Y-m-d", strtotime($model->optioned_approved_at));
            }

            if ($model->optioned_end_at) {
                $model->optioned_end_at = date("Y-m-d", strtotime($model->optioned_end_at));
            }

            if ($model->optioned_by) {
                $model->optioned_by = $model->optionedUser->name . ' (#'.$model->optionedUser->id.')';
            }
        }

        if (!$model) {
            $model = new Model;
        }

        $model->start_at = date("Y-m-d\TH:i:s", strtotime($startAt));
        $model->end_at = date("Y-m-d\TH:i:s", strtotime($endAt));
        $model->bidding_start_at = date("Y-m-d\TH:i:s", strtotime($biddingStartAt));

        $form = $formBuilder->create(EditForm::class, [
            'method' => 'POST',
            'url'    => route('backend.properties.store', ['event' => $event->id, 'model' => $model ? $model->id : null]),
            'model'  => $model
        ], [
            'event' => $event
        ]);

        return view('backend.properties.edit', compact('form', 'model', 'event'));
    }

    public function store(FormBuilder $formBuilder, Event $event, Model $model = null)
    {
        $form = $formBuilder->create(EditForm::class, [], [
            'event' => $event
        ]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $formValues = $form->getFieldValues();

        if (!$model) {
            $model = new Model;
        }

        if ($formValues['sold_closing_at']) {
            $formValues['sold_closing_at'] = date('Y-m-d H:i:s', strtotime($formValues['sold_closing_at']));
        } else {
            $formValues['sold_closing_at'] = null;
        }

        if ($formValues['optioned_approved_at']) {
            $formValues['optioned_approved_at'] = date('Y-m-d H:i:s', strtotime($formValues['optioned_approved_at']));
        } else {
            $formValues['optioned_approved_at'] = null;
        }

        if ($formValues['optioned_end_at']) {
            $formValues['optioned_end_at'] = date('Y-m-d H:i:s', strtotime($formValues['optioned_end_at']));
        } else {
            $formValues['optioned_end_at'] = null;
        }

        $formValues['start_at'] = date('Y-m-d H:i:s', strtotime($formValues['start_at']));
        $formValues['bidding_start_at'] = date('Y-m-d H:i:s', strtotime($formValues['bidding_start_at']));
        $formValues['end_at'] = date('Y-m-d H:i:s', strtotime($formValues['end_at']));


        if ($formValues['optioned_by']) {
            $formValues['optioned_by'] = str_replace(['(#', ')'], '', substr($formValues['optioned_by'], strpos($formValues['optioned_by'], '(#')));

            $user = \App\User::find($formValues['optioned_by']);

            if (!$user) {
                $formValues['optioned_by'] = null;
            }
        }

        $model->fill($formValues);
        $model->cancel_reason = @$formValues['cancel_reason'];
        $model->save();
        $model->addToEvent($event->id, $formValues['number']);

        return redirect()->route('backend.properties.index', ['event' => $event->id]);
    }

    public function import(Request $request, Event $event)
    {
        $id = $request->get('id');

        if (!$id) {
            return redirect()->route('backend.properties.index', ['event' => $event->id]);
        }

        $property = json_decode(file_get_contents('https://realityrealtypr.com/reposubasta/?id='.$id), true);
        $oldid = $property['id'];
        unset($property['id']);

        $startAt = $event->start_at;
        $endAt = $event->end_at;
        $biddingStartAt = $event->bidding_start_at;

        $model = null;
        $found = false;

        if ($property['internal_number']) {
            $model = Model::where('internal_number', '=', $property['internal_number'])->first();
            $found = true;
        }

        if (!$model) {
            $model = new Model;
        }

        $model->fill($property);
        $model->start_at = date("Y-m-d\TH:i:s", strtotime($startAt));
        $model->end_at = date("Y-m-d\TH:i:s", strtotime($endAt));
        $model->bidding_start_at = date("Y-m-d\TH:i:s", strtotime($biddingStartAt));

        for($i = 1; $i <= 10; $i++) {
            if (isset($property['Photos'][$i-1])) {
                $model['image'.$i] = $property['Photos'][$i-1]['large'];
            }
        }

        $model->proccessImportImages($oldid);

        $model->save();
        $model->addToEvent($event->id);
        $model->addTag(7);

        return redirect()->route('backend.properties.index', ['event' => $event->id]);
    }

    public function logs(Request $request, Event $event, Model $model)
    {
        $models = App\Models\PropertyStatusLog::with(['oldStatus', 'newStatus', 'optionedBy'])
            ->where('property_id', '=', $model->id)
            ->orderBy('created_at', 'desc')->get();

        return view('backend.properties.logs', compact('model', 'event', 'models'));
    }

    public function auction(Request $request, FormBuilder $formBuilder, Event $event, Model $model)
    {
        //Suspense
        if ($request->ajax()) {
            if ($request->has('suspense')) {
                event(new \App\Events\Suspense((bool)$request->get('suspense')));
            }

            if ($request->has('celebrate')) {
                event(new \App\Events\Celebrate((bool)$request->get('celebrate')));
            }

            die('Done');
        }


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
        if (!$request->has('loaded')) {
            $model->load(['type', 'status']);
            event(new \App\Events\Auction($model, $modelEvent));
        }

        return view('backend.properties.auction', compact('form', 'model', 'event', 'modelEvent', 'bids', 'winner', 'users'));
    }

    public function nextAuction(Request $request, Event $event)
    {
        $number = $request->get('number');
        $operator = $request->get('operator', '>');

        $modelEvent = DB::table('property_event')
            ->where('number', $operator, $number)
            ->where('event_id', '=', $event->id)
            ->orderBy('number', ($operator == '>' ? 'asc' : 'desc'))->first();

        if (!$modelEvent) {
            Session::flash('success', __('Theres no more properties in this event'));
            return redirect()->route('backend.properties.index', ['event' => $event->id]);
        }

        return redirect()->route('backend.properties.auction', ['event' => $event->id, 'model' => $modelEvent->property_id]);
    }

    public function finishAuction(Request $request, Event $event, Model $model)
    {
        event(new \App\Events\Suspense(false));

        $bid = $model->endAuction($event->id, $request->get('status_id'));

        if ($bid) {
            Session::flash('success', "Auction closed with winner!");
        } else {
            Session::flash('success', "Auction closed without winner!");
        }

        return redirect()->route('backend.properties.auction', ['event' => $event->id, 'model' => $model->id, 'loaded' => true]);
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
            $model->clearStatus();

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

    public function addTag(Request $request, FormBuilder $formBuilder, Event $event, Model $model)
    {
        //Handle post
        if ($request->isMethod('post')) {
            $form = $formBuilder->create(AddTagForm::class);

            if (!$form->isValid()) {
                return redirect()->back()->withErrors($form->getErrors())->withInput();
            }

            $formValues = $form->getFieldValues();

            $model->addTag($formValues['tag_id']);

            Session::flash('success', __('Property added to tag!'));

            return redirect()->route('backend.properties.index', ['event' => $event->id]);
        }

        $form = $formBuilder->create(AddTagForm::class, [
            'method' => 'POST',
            'url'    => route('backend.properties.add-tag-post', ['event' => $event->id, 'model' => $model->id]),
            'model'  => $model
        ]);

        return view('backend.properties.add-tag', compact('form', 'model'));
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

        $total = (clone $baseQuery)->count();
        $propertiesByCity = (clone $baseQuery)->orderBy('properties.city', 'asc');
        $propertiesByNumber = (clone $baseQuery)->orderBy('property_event.number', 'asc');

        set_time_limit(-1);
        $pdf = \DomPDF::loadView('frontend.pdf', compact('total', 'event', 'propertiesByCity', 'propertiesByNumber'))->setPaper('Letter');
        return $pdf->download('properties.pdf');
    }

    public function importCSV(Request $request, FormBuilder $formBuilder, Event $event)
    {
        //Handle post
        if ($request->isMethod('post')) {
            $form = $formBuilder->create(ImportForm::class);

            if (!$form->isValid()) {
                return redirect()->back()->withErrors($form->getErrors())->withInput();
            }

            $formValues = $form->getFieldValues();

            $rows = array_map('str_getcsv', file($formValues['csv']->getPathName()));

            $header = $rows[0];
            unset($rows[0]);

            $types = [];
            foreach(App\Models\PropertyType::all() as $type) {
                $types[$type->slug] = $type->id;
            }

            $investors = [];
            foreach(App\Models\Investor::all() as $investor) {
                $investors[$investor->slug] = $investor->id;
            }

            foreach($rows as $row) {

                $row = array_combine($header, $row);

                $sourceId = $row['INVESTOR'].'-'.$row['PROPERTY ID'];

                $model = Model::where('source_id', '=', $sourceId)->first();

                if (!$model) {
                    $model = new Model;
                }

                //Get investor
                $investorId = @$investors[$row['INVESTOR']];

                if (!$investorId) {
                    $investor = new App\Models\Investor();
                    $investor->name = $row['INVESTOR'];
                    $investor->slug = $row['INVESTOR'];
                    $investor->save();
                    $investorId = $investor->id;
                }

                $values = [];
                $values['number']         = @$row['CATALOG ORDER'] ? $row['CATALOG ORDER'] : null;
                $values['type_id']        = @$types[trim($row['PROPERTY TYPE'])];
                $values['investor_id']    = $investorId;
                $values['investor_reference_id'] = $row['PROPERTY ID'] ? $row['PROPERTY ID'] : null;
                $values['source_id']      = $sourceId;
                $values['address']        = @$row['PROPERTY ADDRESS'];
                $values['city']           = @$row['CITY'];
                $values['latitude']       = @$row['Latitude'] ? $row['Latitude'] : null;
                $values['longitude']      = @$row['Longitude'] ? $row['Longitude'] : null;
                $values['description_es'] = @$row['DESCRIPTION'];
                $values['description_en'] = @$row['DESCRIPTION'];
                $values['sqf_area']       = @$row['Building Size (SqFt)'] ? floatval(trim(str_replace(['$', ','], '', $row['Building Size (SqFt)']))) : null;
                $values['cuerdas']        = @$row['Land Parcel Size (Cdas)'] ? floatval(trim(str_replace(['$', ','], '', $row['Land Parcel Size (Cdas)']))) : null;
                $values['sqm_area']       = @$row['Land Parcel Size (Cdas)'] ? floatval(trim(str_replace(['$', ','], '', $row['Land Parcel Size (Cdas)']))) * 3930.34 : null;
                $values['bedrooms']       = @$row['Bedrooms'] ? intval($row['Bedrooms']) : null;
                $values['bathrooms']      = @$row['Bathrooms'] ? intval($row['Bathrooms']) : null;
                $values['catastro']       = @$row['CRIM Tax ID'] ? $row['CRIM Tax ID'] : null;
                $values['price']          = floatval(trim(str_replace(['$', ','], '', $row['LISTING PRICE'])));
                $values['lister_broker']  = @$row['BROKER'] ? $row['BROKER'] : null;
                $values['start_at']       = date('Y-m-d H:i:s', strtotime($event->start_at));
                $values['end_at']         = date('Y-m-d H:i:s', strtotime($event->end_at));

                $model->fill($values);
                try {
                    $model->save();

                    $model->addToEvent($event->id, $values['number']);
                } catch (\Exception $e) {
                    var_dump($row);
                    echo $e->getMessage().'<br />';
                }
            }

            Session::flash('success', __('Properties imported!'));
            //return redirect(route('backend.properties.index', ['event' => $event->id]));
        }

        $form = $formBuilder->create(ImportForm::class, [
            'method' => 'POST',
            'url'    => route('backend.properties.importcsv', ['event' => $event->id])
        ]);

        return view('backend.properties.import', compact('form', 'event'));
    }

    public function photos(Request $request, Event $event, Model $model)
    {
        if ($request->isMethod('post')) {
            $files = $request->get('files', []);

            if ($files) {
                $model->fill($files);
                $model->proccessImages();
                $model->save();
            }

            die('DONE');
        }

        $bucket = env('AWS_BUCKET');
        $accessKeyId = env('AWS_ACCESS_KEY_ID');
        $secret = env('AWS_SECRET_ACCESS_KEY');
        $policy = base64_encode(json_encode(array(
            // ISO 8601 - date('c'); generates uncompatible date, so better do it manually
            'expiration' => date('Y-m-d\TH:i:s.000\Z', strtotime('+1 day')),
            'conditions' => array(
                array('bucket' => $bucket),
                array('acl' => 'public-read'),
                array('starts-with', '$key', ''),
                array('starts-with', '$Content-Type', 'image/'),
                array('starts-with', '$name', ''),
                array('starts-with', '$Filename', ''),
            )
        )));
        $signature = base64_encode(hash_hmac('sha1', $policy, $secret, true));

        $photos = [];

        for($i = 1; $i <= 10; $i++) {
            if ($model['image'.$i]) {
                $photos[] = $model['image'.$i];
            }
        }

        return view('backend.properties.photos', compact('photos', 'model', 'event', 'bucket', 'accessKeyId', 'policy', 'signature'));
    }

    public function photoDelete(Request $request, Event $event, Model $model)
    {
        $photo = $request->get('photo');

        $model["image{$photo}"] = null;
        $model["image{$photo}_thumb"] = null;

        for($i = $photo + 1; $i <= 10; $i++) {
            if ($model["image{$i}"]) {
                $model["image". ($i - 1)] = $model["image{$i}"];
                $model["image{$i}"] = null;

                $model["image". ($i - 1).'_thumb'] = $model["image{$i}_thumb"];
                $model["image{$i}_thumb"] = null;
            }
        }

        //Make sure main image exist
        if (!$model->getMainImage()) {
            $model->main_image = 1;
        }

        $model->save();

        die('DONE');
    }


    public function photoMain(Request $request, Event $event, Model $model)
    {
        $photo = $request->get('photo');

        $model["main_image"] = $photo;
        $model->save();

        die('DONE');
    }

    public function delete(Event $event, Model $model)
    {
        $model->delete();

        Session::flash('success', __('Property deleted!'));
        return redirect(route('backend.properties.index', ['event' => $event->id]));
    }

    public function select(Request $request, Event $event, Model $model)
    {
        $clear = $request->get('clear');

        if ($clear) {
            \Session::put('selected', []);
        } else {
            $selected = session()->pull('selected', []);

            if(($key = array_search($model->id, $selected)) !== false) {
                unset($selected[$key]);
            } else {
                $selected[] = $model->id;
            }

            \Session::put('selected', $selected);
        }
    }

    public function printSelect(Request $request, Event $event)
    {
        $lang = $request->get('lang');
        $selected = session()->get('selected', []);

        $url = route('frontend.page', ['pageSlug' => 'properties', 'locale' => $lang, 'pdftest' => 1, 'event_type' => 'LIVE', 'id' => $selected]);

        return redirect('http://pdfmyurl.com/saveaspdf?url=' . urlencode($url));
    }

    public function editBid(Request $request, FormBuilder $formBuilder, Event $event, Model $model)
    {
        $bidId = $request->get('bid_id');

        if ($bidId) {
            $bid = Bid::find($bidId);
        } else {
            $bid = new Bid;
        }

        $form = $formBuilder->create(BidForm::class, [
            'method' => 'POST',
            'url'    => route('backend.properties.bid.store', ['event' => $event->id, 'model' => $model->id, 'bid_id' => $bidId]),
            'model'  => $bid
        ]);

        return view('backend.properties.bid.edit', compact('form', 'model', 'event'));
    }

    public function bidStore(Request $request, FormBuilder $formBuilder, Event $event, Model $model)
    {
        $target = $request->get('target', 'auction');
        $bidId = $request->get('bid_id', false);
        $form = $formBuilder->create(BidForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $formValues = $form->getFieldValues();

        $userEvent = false;

        if ($formValues['number']) {
            $userEvent = DB::table('user_event')
                ->leftJoin('users', 'users.id', '=', 'user_event.user_id')
                ->where('number', '=', $formValues['number'])
                ->where('event_id', '=', $event->id)->first();
        }

        if ($bidId) {
            $bid = Bid::find($bidId);
        } else {
            $bid = new Bid;
        }

        $bid->user_id = $userEvent ? $userEvent->user_id : null;
        $bid->property_id = $model->id;
        $bid->event_id = $event->id;
        $bid->offer = $formValues['offer'];
        $bid->is_winner = false;
        $bid->save();

        //Broadcast
//        if (!$bidId && $target == 'auction') {
//            event(new \App\Events\Bid($bid, $userEvent));
//        }

        Session::flash('success', __('Offer saved successfully!'));

        return redirect()->route('backend.properties.'.$target, ['event' => $event->id, 'model' => $model->id, 'loaded' => true]);
    }

    public function bids(Request $request, FormBuilder $formBuilder, Event $event, Model $model)
    {
        $modelEvent = $model->getEventData($event->id);
        $bids = $model->getBids($event->id);

        //Users
        $users = User::select('users.*', 'users.name', 'user_event.number')
            ->where('user_event.event_id', '=', $event->id)
            ->where('user_event.is_active', '=', true)
            ->join('user_event', 'user_event.user_id', '=', 'users.id')->get();

        $form = $formBuilder->create(BidForm::class, [
            'method' => 'POST',
            'class' => 'form-horizontal',
            'url'    => route('backend.properties.bid.store', ['event' => $event->id, 'model' => $model->id, 'target' => 'bid.index']),
        ]);

        return view('backend.properties.bid.index', compact('form', 'model', 'event', 'modelEvent', 'bids', 'users'));
    }
}
