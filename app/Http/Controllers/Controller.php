<?php

namespace Ghi\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * The signed in user
     * @var
     */
    protected $user;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->user = \Auth::user();
        view()->share('user', $this->user);
        view()->share('signedIn', \Auth::check());
    }
}
