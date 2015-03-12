<?php namespace Ghi\Core\Http\Controllers;

use Ghi\Core\Domain\Usuarios\UserRepository;
use Illuminate\Contracts\Auth\Guard;

class PagesController extends Controller {

    /**
     * Create a new controller instance.
     *
     */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function home()
	{
		return view('pages.home');
	}

    /**
     * Muestra una lista de obras asociadas con el usuario
     *
     * @param UserRepository $repository
     * @param Guard $auth
     * @return \Illuminate\View\View
     */
    public function obras(UserRepository $repository, Guard $auth)
    {
        $obras = $repository->getObras($auth->id());

        return view('pages.obras')
            ->withObras($obras);
    }

}
