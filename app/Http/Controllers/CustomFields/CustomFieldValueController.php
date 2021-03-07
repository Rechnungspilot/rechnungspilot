<?php

namespace App\Http\Controllers\CustomFields;

use App\Http\Controllers\Controller;
use App\Models\CustomFields\CustomField;
use App\Models\CustomFields\CustomFieldValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CustomFieldValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $type, Model $model)
    {
        if ($request->wantsJson()) {
            return $model->customfields()
                ->with('customfield')
                ->get();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $type, Model $model)
    {
        $validatedData = $request->validate([
            'custom_field_ids' => 'required|array',
        ]);

        $customfields = CustomField::find($validatedData['custom_field_ids']);

        foreach($customfields as $customfield)
        {
            $customfieldvalue = $model->customfields()->create([
                'company_id' => $customfield->company_id,
                'custom_field_id' => $customfield->id,
                'value' => $customfield->default_value,
            ]);
        }

        if ($request->wantsJson()) {
            return $customfieldvalue;
        }

        return back()
            ->with('status', 'Individuelles Feld erstellt!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomFields\CustomFieldValue  $customfieldvalue
     * @return \Illuminate\Http\Response
     */
    public function show(CustomFieldValue $customfieldvalue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomFields\CustomFieldValue  $customfieldvalue
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomFieldValue $customfieldvalue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomFields\CustomFieldValue  $customfieldvalue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomFieldValue $customfieldvalue)
    {
        $validatedData = $request->validate([
            'value' => 'nullable|string',
        ]);

        $customfieldvalue->update($validatedData);

        return $customfieldvalue->load('customfield');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomFields\CustomFieldValue  $customfieldvalue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, CustomFieldValue $customfieldvalue)
    {
        if ($customfieldvalue->isDeletable()) {
            $customfieldvalue->delete();
        }

        if ($request->wantsJson())
        {
            return;
        }

        return back();
    }
}
