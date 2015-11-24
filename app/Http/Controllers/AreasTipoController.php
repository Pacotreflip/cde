<?php

namespace Ghi\Http\Controllers;

use Ghi\Http\Requests;
use Laracasts\Flash\Flash;
use Illuminate\Http\Request;
use Ghi\Equipamiento\Areas\AreaTipo;
use Ghi\Http\Requests\CreateAreaTipoRequest;
use Ghi\Http\Requests\UpdateAreaTipoRequest;
use Ghi\Equipamiento\Areas\AreasTipo;

class AreasTipoController extends Controller
{
    /**
     * @tipos TipoAreaRepository
     */
    protected $areas_tipo;

    /**
     *
     * @param AreasTipo $areas_tipo
     */
    public function __construct(AreasTipo $areas_tipo)
    {
        $this->middleware('auth');
        $this->middleware('context');

        $this->areas_tipo = $areas_tipo;

        parent::__construct();
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tipo = null;

        if ($request->has('tipo')) {
            $tipo = $this->areas_tipo->getById($request->get('tipo'));
            $descendientes = $tipo->children()->defaultOrder()->get();
        } else {
            $descendientes = $this->areas_tipo->getNivelesRaiz();
        }

        return view('areas-tipo.index')
            ->withTipo($tipo)
            ->withDescendientes($descendientes);

        return view('areas-tipo.index')->withTipos($tipos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $tipos = [null => 'Ninguno'] + $this->areas_tipo->getListaTipos();

        return view('areas-tipo.create')
            ->withTipos($tipos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAreaTipoRequest $request
     * @return Response
     */
    public function store(CreateAreaTipoRequest $request)
    {
        $parent = AreaTipo::find($request->get('parent_id'));
        $tipo = $this->nuevoTipo($request->all(), $parent);

        Flash::success('El nuevo tipo de area fue agregado.');

        return redirect()->route('tipos.index', $parent ? ['tipo' => $parent->id] : []);
    }

    /**
     * Crea un nuevo tipo de area.
     * 
     * @param  array $data
     * @param  AreaTipo|null $parent
     * @return AreaTipo
     */
    protected function nuevoTipo($data, $parent)
    {
        $tipo = AreaTipo::nuevo($data)
            ->enObra($this->getObraEnContexto())
            ->dentroDe($parent);

        $tipo->save();

        return $tipo;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $tipo = $this->areas_tipo->getById($id);
        $tipos = [null => 'Inicio'] + $this->areas_tipo->getListaTipos();

        return view('areas-tipo.datos-generales')
            ->withTipo($tipo)
            ->withTipos($tipos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAreaTipoRequest $request
     * @param  int $id
     * @return Response
     */
    public function update(UpdateAreaTipoRequest $request, $id)
    {
        $tipo = $this->areas_tipo->getById($id);
        $parent = AreaTipo::find($request->get('parent_id'));

        $tipo->fill($request->all());

        if ($request->has('move_up')) {
            $tipo->up();
            return back();
        }

        if ($request->has('move_down')) {
            $tipo->down();
            return back();
        }

        $tipo->dentroDe($parent);
        $this->areas_tipo->save($tipo);

        Flash::success('Los cambios fueron guardados.');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $tipo = $this->areas_tipo->getById($id);
        $isRoot = $tipo->isRoot();

        if (! $isRoot) {
            $parent_id = $tipo->parent->id;
        }

        $this->areas_tipo->delete($tipo);

        Flash::message('El tipo de area fue borrado.');

        return redirect()->route('tipos.index', $isRoot ? [] : ['tipo' => $parent_id]);
    }
}
