<?php

namespace Ghi\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Validator;
use Ghi\Http\Controllers\Controller;

class AuthController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * @var string
     */
    protected $redirectPath = '/obras';

    protected $loginPath = '/auth/login';

    /**
     * Create a new authentication controller instance.
     *
     */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'getLogout']);
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
            'usuario' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

	/**
	 * Handle a login request to the application.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function postLogin(Request $request)
	{
        $this->validate($request, [
            'usuario' => 'required', 'clave' => 'required',
        ]);

        $credentials = $request->only('usuario', 'clave');

        if (auth()->attempt($credentials, $request->has('remember_me'))) {
            flash("Bienvenido " . auth()->user()->nombre . "!");

            return redirect($this->redirectPath());
        }

        return redirect($this->loginPath())
            ->withInput($request->only('usuario', 'remember_me'))
            ->withErrors([
                'usuario' => $this->getFailedLoginMessage(),
            ]);
	}

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return 'El nombre de usuario o contraseña son invalidos.';
    }

	/**
	 * Log the user out of the application.
	 *
	 * @return Response
	 */
	public function getLogout()
	{
		auth()->logout();

		session()->flush();

		return redirect('/');
	}
}
