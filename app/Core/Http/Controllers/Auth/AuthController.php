<?php namespace Ghi\Core\Http\Controllers\Auth;

use Ghi\Core\Http\Controllers\Controller;
use Ghi\Core\Http\Requests\Auth\LoginRequest;
use Ghi\Core\Domain\Usuarios\UserRepository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Support\Facades\Session;
use Laracasts\Flash\Flash;

class AuthController extends Controller {

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

    protected $auth;

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard $auth
     * @param  \Illuminate\Contracts\Auth\Registrar $registrar
     */
    public function __construct(Guard $auth, Registrar $registrar)
    {
        $this->auth = $auth;
        $this->registrar = $registrar;

        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Show the application login form.
     *
     * @return Response
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  LoginRequest $request
     * @param UserRepository $repository
     * @return Response
     */
    public function postLogin(LoginRequest $request, UserRepository $repository)
    {
        $usuario = $request->get('usuario');
        $clave = $request->get('clave');

        try
        {
            $user = $repository->getByNombreUsuario($usuario);

            if (md5($clave) !== $user->clave)
            {
                throw new Exception('Usuario y/o clave no válidos.');
            }

            $this->auth->login($user);

            Flash::message("Bienvenido {$user->nombre}!");

            return redirect()->route('pages.home');
        }
        catch (Exception $e)
        {
            Flash::error($e->getMessage());

            return redirect()->route('auth.login')->withinput();
        }

    }

    /**
     * Log the user out of the application.
     *
     * @return Response
     */
    public function getLogout()
    {
        $this->auth->logout();

        Session::flush();

        return redirect('/');
    }

}
