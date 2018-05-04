<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PropertyType;
use View;
use App;

class FrontendController extends Controller
{
    public function page(Request $request, $locale, $pageSlug = null) {
        App::setLocale($locale);

        if (!in_array($locale, ['es', 'en'])) {
            dd($locale);
        }

        $pageSlug = $pageSlug ?: 'homepage';
        $page = Page::where('slug_es', '=', $pageSlug)->orWhere('slug_en', '=', $pageSlug)->first();
        $data = ['page' => $page];

        if (method_exists($this, $pageSlug)) {
            $data = array_merge($data, $this->{$pageSlug}($request));
        }

        $view = $page ? $page->slug_en : $pageSlug;

        return view("frontend.{$view}", $data);
    }

    public function homepage($request) {
        $types = [];
        $types[''] = __('All');
        foreach (PropertyType::all() as $type) {
            $types[$type->id] = $type->name;
        }

        return compact('types');
    }

    public function properties($request) {

        $types = [];
        $types[''] = __('All');
        foreach (PropertyType::all() as $type) {
            $types[$type->id] = $type->name;
        }

        $query = Property::select('properties.*', 'property_event.number')
            ->join('property_event', function($join) {
                $join->on('property_event.property_id', '=', 'properties.id');
            })
            ->join('events', function($join) {
                $join->on('events.id', '=', 'property_event.event_id');
            })
            ->where('events.start_at', '<=', date('Y-m-d H:i:s'))
            ->where('events.end_at', '>', date('Y-m-d H:i:s'))
            ->where('events.is_active', '=', true)
            ->where('property_event.is_active', '=', true);

        if ($type = $request->get('type')) {
            $query->where('properties.type_id', '=', $type);
        }

        if ($keywords = $request->get('keywords')) {
            $query->where('properties.address', 'LIKE', "%{$keywords}%");
            $query->orWhere('properties.city', 'LIKE', "%{$keywords}%");
            $query->orWhere('properties.id', 'LIKE', "%{$keywords}%");
        }

        //dd($query->toSql());

        $properties = $query->paginate(9);

        return compact('types', 'properties');
    }
}
