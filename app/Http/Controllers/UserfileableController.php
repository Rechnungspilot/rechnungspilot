<?php

namespace App\Http\Controllers;

use App\Userfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserfileableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $typ, $model)
    {
        return $model->userfiles()
            ->search($request->input('searchtext'))
            ->with('user')
            ->orderBy('created_at', 'DESC')
            ->paginate(5);
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
    public function store(Request $request, string $typ, $model)
    {
        $validatedData = $request->validate($this->uploadRules($typ));

        $userfiles = [];
        foreach ($validatedData['files'] as $key => $file) {
            $userfiles[] = Userfile::fromUploadedFile($file, $model);
        }

        if ($request->wantsJson()) {
            return $userfiles;
        }

        return back()
            ->with('status', 'Datei hochgeladen!');
    }

    protected function uploadRules(string $type)
    {

        if (in_array($type, ['ausgaben', 'einnahmen']))
        {
            return [
                'files' => 'required|array|size:1',
                'files.*' => 'required|file|max:51200|mimes:pdf',
            ];
        }

        return [
            'files' => 'required|array',
            'files.*' => 'required|file|max:51200|mimes:' . join(',', UserFile::MIME_TYPES),
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Userfiles  $userfiles
     * @return \Illuminate\Http\Response
     */
    public function show(Userfile $userfile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Userfile  $userfile
     * @return \Illuminate\Http\Response
     */
    public function edit(Userfile $userfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Userfile  $userfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Userfile $userfile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Userfile  $userfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Userfile $userfile)
    {
        //
    }
}
