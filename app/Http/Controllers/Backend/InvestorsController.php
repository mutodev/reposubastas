<?php

namespace App\Http\Controllers\Backend;

use App\Models\Investor as Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Forms\Backend\Investor\EditForm;
use Kris\LaravelFormBuilder\FormBuilder;

class InvestorsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $models = Model::all();

        return view('backend.investors.index', compact('models'));
    }

    public function edit(FormBuilder $formBuilder, Model $model = null)
    {
        $form = $formBuilder->create(EditForm::class, [
            'method' => 'POST',
            'url'    => route('backend.investors.store', ['model' => $model ? $model->id : null]),
            'model'  => $model
        ]);

        return view('backend.investors.edit', compact('form', 'model'));
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

        $model->fill($formValues);
        $model->save();

        return redirect()->route('backend.investors.index');
    }
}
