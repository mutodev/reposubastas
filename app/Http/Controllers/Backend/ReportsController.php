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
        $properties = Property::select('properties.*', 'property_event.number as catalog_number', 'property_event.is_active', 'user_event.number as optioned_user_number')
            ->with(['type', 'status', 'investor', 'optionedUser'])
            ->join('property_event', function ($join) use ($event) {
                $join->on('property_event.property_id', '=', 'properties.id');
                $join->on('property_event.event_id', '=', \DB::raw($event->id));
            })
            ->leftJoin('user_event', function ($join) use ($event) {
                $join->on('user_event.user_id', '=', 'properties.optioned_by');
                $join->on('user_event.event_id', '=', \DB::raw($event->id));
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

//            $lastBid = null;
//            if ($status && in_array($status->slug, ['APPROVED', 'OPTIONED', 'SOLD'])) {
//                $lastBid = $property->getBids($event->id)->first();
//            }

            fputcsv($file, [
                $property->catalog_number,
                ($property->investor_id ? $property->investor->name : null),
                ($property->investor_id ? $property->investor_reference_id : null),
                $property->address,
                $property->city,
                $property->lister_broker,
                $property->seller_broker,
                ($property->optioned_user_number ? $property->optioned_user_number : $property->user_number),
                $status ? $status->name_en : null,
                $property->sold_closing_at,
                $property->optioned_price,
                ($property->optioned_by ? $property->optionedUser->name : null),
                $property->check_amount,
                $property->optioned_method,
                $property->comments
            ]);
        }

        exit();
    }

}
