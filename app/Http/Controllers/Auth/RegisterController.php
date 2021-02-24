<?php

namespace App\Http\Controllers\Auth;

use App\Banks\Account;
use App\Company;
use App\Contacts\Contact;
use App\Http\Controllers\Controller;
use App\Item;
use App\Mail\CompanyRegistered;
use App\Receipts\Abos\Abo;
use App\Receipts\Boilerplate;
use App\Receipts\Delivery;
use App\Receipts\Expense;
use App\Receipts\Income;
use App\Receipts\Invoice;
use App\Receipts\Order;
use App\Receipts\Quote;
use App\Receipts\Term;
use App\Role;
use App\Unit;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validatedData = $this->validator($request->all())->validate();

        $company = $this->createCompany($validatedData);
        $validatedData['company_id'] = $company->id;

        $user = $this->create($validatedData);
        $user->companies()->attach($company->id);

        event(new Registered($user));

        Mail::to(config('app.email'))
            ->queue(new CompanyRegistered($user));

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $request->session()->push('user.company.id', $user->company_id);

        return redirect($this->redirectPath());
    }

    protected function createCompany(array $data) : Company
    {
        $charge_at = today()->add(1, 'months');

        $company = Company::create([
            'email' => $data['email'],
            'name' => 'Meine Firma',
            'abo_name_format' => '#DATUM-JJ#-#NUMMER-4#',
            'invoice_name_format' => '#DATUM-JJ#-#NUMMER-4#',
            'order_name_format' => '#DATUM-JJ#-#NUMMER-4#',
            'quote_name_format' => '#DATUM-JJ#-#NUMMER-4#',
            'delivery_name_format' => '#DATUM-JJ#-#NUMMER-4#',
            'expense_name_format' => '#DATUM-JJ#-#NUMMER-4#',
            'price' => 10,
            // 'charging_start_at' => $charge_at,
            // 'charging_next_at' => $charge_at,
        ]);

        $company->setup();

        return $company;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'firstname' => 'Mein',
            'lastname' => 'Name',
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'company_id' => $data['company_id'],
        ]);

        $role = Role::create([
            'company_id' => $data['company_id'],
            'name' => 'admin',
        ]);
        $role->givePermissionTo(Permission::all());

        $user->syncRoles($role->id);

        return $user;
    }
}
