<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Illuminate\Http\Request;
use Ghi\Http\Controllers\Controller;
use Ghi\Core\Contracts\UserRepository;

class PagesController extends Controller
{
    /**
     * PagesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');

        parent::__construct();
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
        $obras->setPath('obras');

        return view('pages.obras')->withObras($obras);
    }
}
