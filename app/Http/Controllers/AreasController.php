<?php

namespace Ghi\Http\Controllers;

use Ghi\Area;
use Illuminate\Http\Request;

use Ghi\Http\Requests;
use Ghi\Http\Controllers\Controller;
use Ghi\Repositories\AreaRepository;

class AreasController extends Controller
{

    /**
     * @var AreaRepository
     */
    private $areas;

    /**
     * AreasController constructor.
     *
     * @param AreaRepository $areas
     */
    public function __construct(AreaRepository $areas)
    {
        $this->middleware('auth');
        $this->areas = $areas;
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->has('area')) {
            $areas = $this->areas->getDescendientesDe($request->get('area'));
        } else {
            $areas = $this->areas->getNivelesRaiz();
        }

        return view('areas.index')->withAreas($areas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
