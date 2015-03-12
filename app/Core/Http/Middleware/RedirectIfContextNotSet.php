<?php namespace Ghi\Core\Http\Middleware;

use Closure;
use Ghi\Core\Services\Context;
use Laracasts\Flash\Flash;
use Redirect;
use Illuminate\Support\Facades\URL;

class RedirectIfContextNotSet {

    /**
     * @var RedirectIfContextNotSet
     */
    private $context;

    /**
     * @var Flash
     */
    private $flash;

    /**
     * @param Context $context
     * @param Flash $flash
     */
    function __construct(Context $context, Flash $flash)
    {
        $this->context = $context;
        $this->flash = $flash;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->context->notEstablished())
        {
            Flash::error('Lo sentimos, debe seleccionar una obra para ver esta información!');

            return redirect()->to(URL::route('pages.obras'));
        }

        return $next($request);
    }
} 