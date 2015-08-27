<?php

namespace Sacranet\Http\Controllers;

use Illuminate\Http\Request;

use Sacranet\Http\Requests;
use Sacranet\Http\Controllers\Controller;
use Sacranet\Http\Requests\TurmaRequest;
use Sacranet\Serie;
use Sacranet\Turma;

class TurmaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('turma.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(TurmaRequest $request)
    {
        if($request->ajax()){



          $serie =  Serie::create($request->except( ['turmas'] ));
          $turmas =  $request->input('turmas');

          foreach($turmas as $turma){

              $serie->turmas()->save(Turma::create(['letra' => $turma ]));

          }

            return response()->json(['sucesso' => 'Turma Criada com Sucesso!']);

            //

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $serie = Serie::find($id);
        $turmas = [];
        foreach( $serie->turmas->toArray() as $turma){
          $turmas[] = $turma['letra'];
        }

        return view('turma.edit',compact('serie','turmas'));

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