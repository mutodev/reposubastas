<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PropertyType;
use View;
use App;
use Jenssegers\Date\Date;

class FrontendController extends Controller
{
    public function page(FormBuilder $formBuilder, Request $request, $locale, $pageSlug = null) {
        App::setLocale($locale);
        Date::setLocale($locale);

        if (!in_array($locale, ['es', 'en'])) {
            dd($locale);
        }

        $pageSlug = $pageSlug ?: 'homepage';
        $page = Page::where('slug_es', '=', $pageSlug)->orWhere('slug_en', '=', $pageSlug)->first();
        $data = ['page' => $page];

        if (method_exists($this, $pageSlug)) {
            $data = array_merge($data, $this->{$pageSlug}($formBuilder, $request));
        }

        $view = $page ? $page->slug_en : $pageSlug;

        return view("frontend.{$view}", $data);
    }

    public function homepage(FormBuilder $formBuilder, $request) {
        $types = PropertyType::forSelect();

        $event = App\Models\Event::orderBy('created_at', 'desc')->first();

        return compact('types', 'event');
    }

    public function properties(FormBuilder $formBuilder, $request) {

        $today = date('Y-m-d H:i:s');

        $query = Property::select('properties.*', 'property_event.number', 'events.start_at as event_start_at')
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
            ->orWhereNull('properties.start_at');

        if ($type = $request->get('type')) {
            $query->where('properties.type_id', '=', $type);
        }

        if ($event = $request->get('event')) {
            $query->where('property_event.event_id', '=', $event);
        }

        if ($keywords = $request->get('keywords')) {
            $keywords = "%{$keywords}%";

            $query->whereRaw('(properties.address LIKE ? or properties.city LIKE ? or properties.region_es LIKE ? or properties.region_en LIKE ? or properties.id LIKE ? or property_event.number LIKE ?)', [
                $keywords,
                $keywords,
                $keywords,
                $keywords,
                $keywords,
                $keywords
            ]);
        }

        $properties = $query->orderBy('property_event.number')->paginate(9);

        $types = PropertyType::forSelect();

        return compact('types', 'properties');
    }

    public function property(FormBuilder $formBuilder, $request) {

        $today = date('Y-m-d H:i:s');

        $property = Property::select('properties.*', 'property_event.number', 'events.start_at as event_start_at', 'events.end_at as event_end_at')
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

        $types = PropertyType::forSelect();

        $online = !empty($property->number);

        return compact('types', 'property', 'online');
    }

    public function register($formBuilder, $request) {

        $form = $formBuilder->create(App\Forms\Frontend\User\RegisterForm::class, [
            'method' => 'POST',
            'url'    => route('frontend.page', ['page' => 'register'])
        ]);

        return compact('form');
    }
}
