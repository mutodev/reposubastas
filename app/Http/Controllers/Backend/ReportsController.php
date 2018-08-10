<?php

namespace App\Http\Controllers\Backend;

use App\Models\Property;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class ReportsController extends Controller
{
    public function report(Request $request, Event $event)
    {
        $properties = Property::select('properties.*', 'property_event.number', 'property_event.is_active')
            ->with(['type', 'status', 'investor'])
            ->join('property_event', function ($join) use ($event) {
                $join->on('property_event.property_id', '=', 'properties.id');
                $join->on('property_event.event_id', '=', \DB::raw($event->id));
            })
            ->orderBy('property_event.number')->get();

        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="report.csv";');

        $file = fopen('php://output', 'w');

        fputcsv($file, [
            'ORDEN_CAT',
            'INVERSIONISTA',
            'REO_ID',
            'ADDRESS',
            'CITY',
            'ASSIGNED_BOKER',
            'SELLER_BROKER',
            'PALETA',
            'STATUS',
            'CLOSING_DATE',
            'APPROVED_SALES_PRICE',
            'CLIENT',
            'DEPOSIT_AMOUNT',
            'FORM_OF_PURCHASE',
            'COMMENTS'
        ]);

        foreach ($properties as $property) {

            $status = $property->status_id ? $property->status : null;

            $lastBid = null;
            if ($status && in_array($status->slug, ['APPROVED', 'OPTIONED', 'SOLD'])) {
                $lastBid = $property->getBids($event->id)->first();
            }

            fputcsv($file, [
                $property->number,
                $property->investor->name,
                $property->investor_reference_id,
                $property->address,
                $property->city,
                $property->lister_broker,
                $property->seller_broker,
                ($lastBid ? $lastBid->number : null),
                $status ? $status->name_en : null,
                $property->sold_closing_at,
                ($lastBid ? $lastBid->offer : null),
                ($lastBid ? $lastBid->name : null),
                $property->deposit,
                $property->optioned_method,
                $property->comments
            ]);
        }

        exit();
    }

}
