<?php

namespace App\Http\Controllers\CustomFields;

use App\Http\Controllers\Controller;
use App\Models\CustomFields\CustomField;
use App\Support\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $type)
    {
        $class = Type::class($type);

        if ($request->wantsJson()) {
            return CustomField::where('for', $class)
                ->search($request->input('searchtext'))
                ->orderBy('name', 'ASC')
                ->get();
        }

        return view('customfield.index')
            ->with('type', $type)
            ->with('inputTypes', CustomField::INPUT_TYPES);
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
    public function store(Request $request, string $type)
    {
        $validatedData = $request->validate([
            'input_type' => 'required|string',
            'name' => 'required|string',
        ]);

        $validatedData['company_id'] = auth()->user()->company_id;
        $validatedData['for'] = Type::class($type);
        $validatedData['options'] = [];

        return CustomField::create($validatedData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomFields\CustomField  $customfield
     * @return \Illuminate\Http\Response
     */
    public function show(CustomField $customfield)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomFields\CustomField  $customfield
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomField $customfield)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomFields\CustomField  $customfield
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomField $customfield)
    {
        $validatedData = $request->validate([
            'input_type' => 'required|string',
            'default' => 'required|boolean',
            'name' => 'required|string',
            'options' => 'nullable|string',
            'info' => 'nullable|string',
        ]);

        if ($validatedData['input_type'] == 'select')
        {
            $options = explode("\n", $validatedData['options']);
            $validatedData['options'] = [];
            $i = 0;
            foreach ($options as $key => $value) {
                $validatedData['options'][Str::slug($value)] = $value;
                $i++;
            }
            reset($validatedData['options']);
            $validatedData['default_value'] = $i > 0 ? key($validatedData['options']) : null;
        }

        $customfield->update($validatedData);

        return $customfield;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomFields\CustomField  $customfield
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, CustomField $customfield)
    {
        if ($isDeletable = $customfield->isDeletable()) {
            $customfield->delete();
        }

        if ($request->wantsJson()) {
            return [
                'deleted' => $isDeletable,
            ];
        }

        return redirect(route('customfield.index', [
            'type' => Type::type($customfield->for),
        ]));
    }
}
