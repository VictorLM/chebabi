<?php

namespace Intranet\Http\Controllers\Auth;

use Intranet\User;
use Intranet\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

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
    protected $redirectTo = '/intranet';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('Intranet\Http\Middleware\Admin::class');
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        //$this->guard()->login($user);

        $request->session()->flash('alert-success', 'Usuário cadastrado com sucesso!');
        return redirect()->action('Admin\AdminController@index');
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
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'ramal' => 'required|numeric|digits:3',
            'telefone' => 'max:16',
            'nascimento' => 'max:10',
            'tipo' => [
                'required',
                Rule::in(['admin', 'adv', 'adm', 'fin', 'admjur']),
            ],
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \Intranet\User
     */
    protected function create(array $data)
    {
        if(empty($data['nascimento'])){
            $nascimento = null;
        }else{
            $nascimento = Carbon::parse($data['nascimento'])->format('y-m-d');
        }
        
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'ativo' => TRUE,
            'ramal' => $data['ramal'],
            'telefone' => $data['telefone'],
            'nascimento' => $nascimento,
            'tipo' => $data['tipo'],
            'uaus' => 0,
            'last_login' => Carbon::now(),
            'password' => bcrypt($data['password'])
        ]);
    }
}
