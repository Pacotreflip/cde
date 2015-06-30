<?php

namespace Ghi\Http\Controllers;

use Auth;
use Ghi\Domain\Core\Usuarios\UserRepository;

class PagesController extends Controller
{
    /**
     *
     */
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
	 * Display a listing of the resource.
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
     * @return \Illuminate\View\View
     */
    public function obras(UserRepository $repository)
    {
        $obras = $repository->getObras(auth()->id());

        return view('pages.obras')->withObras($obras);
    }
}
