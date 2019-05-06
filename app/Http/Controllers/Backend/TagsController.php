<?php

namespace App\Http\Controllers\Backend;

use App\Models\PropertyTag as Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Forms\Backend\Property\TagForm as EditForm;
use Kris\LaravelFormBuilder\FormBuilder;

class TagsController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $models = Model::all();

        return view('backend.tags.index', compact('models'));
    }

    public function edit(FormBuilder $formBuilder, Model $model = null)
    {
        $form = $formBuilder->create(EditForm::class, [
            'method' => 'POST',
            'url'    => route('backend.tags.store', ['model' => $model ? $model->id : null]),
            'model'  => $model
        ]);

        return view('backend.tags.edit', compact('form', 'model'));
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

        return redirect()->route('backend.tags.index');
    }
}
