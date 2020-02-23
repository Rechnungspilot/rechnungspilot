<?php

namespace App\Http\Controllers\Receipts\Receipts;

use App\Contacts\Contact;
use App\Http\Controllers\Controller;
use App\Receipts\Receipt;
use App\Todos\Contacts;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $typ, $model)
    {
        if ($request->wantsJson())
        {
            return $model->todos()
                ->with([
                    'contacts.pivot.user',
                    'item',
                    'team',
                    'times',
                ])
                ->orderBy('completed', 'ASC')
                ->orderBy('start_at', 'DESC')
                ->get();
        }
    }

}
