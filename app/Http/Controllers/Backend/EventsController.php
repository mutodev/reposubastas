<?php

namespace App\Http\Controllers\Backend;

use App\Models\Event as Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Forms\Backend\Event\EditForm;
use Kris\LaravelFormBuilder\FormBuilder;

class EventsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $models = Model::paginate(50)->withPath($request->fullUrlWithQuery($request->all()));

        return view('backend.events.index', compact('models'));
    }

    public function edit(FormBuilder $formBuilder, Model $model = null)
    {
        if ($model) {
            $model->start_at = date("Y-m-d\TH:i:s", strtotime($model->start_at));
            $model->end_at = date("Y-m-d\TH:i:s", strtotime($model->end_at));
        }

        $form = $formBuilder->create(EditForm::class, [
            'method' => 'POST',
            'url'    => route('backend.events.store', ['model' => $model ? $model->id : null]),
            'model'  => $model
        ]);

        return view('backend.events.edit', compact('form', 'model'));
    }

    public function store(FormBuilder $formBuilder, Model $model = null)
    {
        $form = $formBuilder->create(EditForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $formValues = $form->getFieldValues();

        if (!$model) {
            $model = new Model;
        }

        if ($formValues['start_at']) {
            $formValues['start_at'] = date('Y-m-d H:i:s', strtotime($formValues['start_at']));
            $formValues['end_at'] = date('Y-m-d H:i:s', strtotime($formValues['end_at']));

            //Update properties
            foreach ($model->properties()->get() as $property) {
                $property->start_at = $formValues['start_at'];
                $property->end_at = $formValues['end_at'];
                $property->save();
            }
        }

        $model->fill($formValues);
        $model->save();

        return redirect()->route('backend.events.index');
    }

    public function view(Model $model)
    {
        $bids = \App\Models\Bid::where('event_id', '=', $model->id)->orderBy('id', 'desc')->get()->unique('property_id')->toArray();

        $bidsTotal = 0;
        foreach ($bids as $bid) {
            $bidsTotal += (float)$bid['offer'];
        }

        return view('backend.events.view', compact('model', 'bidsTotal'));
    }

    public function live(Model $model)
    {
        return view('backend.events.live', compact('model'));
    }
}
