<?php

namespace App\Http\Controllers\Contacts;

use App\Contacts\Interaction;
use App\Contacts\InteractionType;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $type = null, Model $model = null)
    {
        if ($request->wantsJson())
        {
            if (is_null($model))
            {
                return Interaction::interaction($request->input('interaction_id'))
                    ->with([
                        'contact',
                        'type',
                        'person',
                        'interactionable',
                    ])->paginate();
            }
            else
            {
                return $model->interactions()
                    ->interaction($request->input('interaction_id'))
                    ->with([
                        'type',
                        'person',
                        'interactionable',
                    ])->paginate();
            }

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
        $attributes = [
            'company_id' => auth()->user()->company_id,
            'user_id' => auth()->user()->id,
            'interaction_id' => $request->input('interaction_id'),
            'contact_id' => $request->input('contact_id'),
        ];

        return $model->interactions()->create($attributes);
    }

    /**
     * Display the specified resource.
     *
     * @param  Interaction $interaction
     * @return \Illuminate\Http\Response
     */
    public function show(Interaction $interaction)
    {
        $interaction->load([
            'contact',
            'interactionable',
            'interactions',
            'person',
            'type',
            'user',
        ]);

        return view('contact.interaction.show')
            ->with('model', $interaction);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $interaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Interaction $interaction)
    {
        return view('contact.interaction.edit')
            ->with('model', $interaction)
            ->with('types', InteractionType::all())
            ->with('people', $interaction->contact->people);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Interaction $interaction)
    {
        // dd($request->all());

        $validatedData = $request->validate([
            'interaction_type_id' => 'required|exists:interaction_types,id',
            'person_id' => 'required|integer',
            'at' => 'required|date_format:"d.m.Y H:i"',
            'name' => 'nullable|string',
            'text' => 'nullable|string',
        ]);

        $validatedData['at'] = Carbon::createFromFormat('d.m.Y H:i', $validatedData['at']);

        $interaction->update($validatedData);

        return redirect($interaction->path);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Interaction $interaction)
    {
        if ($isDeletable = $interaction->isDeletable()) {
            $interaction->delete();
        }

        if ($request->wantsJson())
        {
            return [
                'deleted' => $isDeletable,
            ];
        }

        return back();
    }
}
