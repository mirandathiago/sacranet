<?php

namespace Sacranet\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Sacranet\Http\Requests;
use Sacranet\Aluno;
use Sacranet\Turma;
use Sacranet\Http\Requests\AlunoRequest;
use Illuminate\Support\Facades\Session;

class AlunoController extends Controller
{


    public function index(Request $request)
    {

        $turmas = Turma::with('serie')->get();
        $busca = $request->get('p');
        $turma = $request->get('t');

        Session::put('url',\URL::full());



        if($busca && $turma) {
            $alunos = Aluno::where('nomealuno','LIKE',"%$busca%")->where('turma_id',$turma)->orderBy('id')->paginate(12);
        }
        elseif($turma){
            $alunos = Aluno::where('turma_id',$turma)->orderBy('numero')->paginate(70);
        }
        elseif($busca){
            $alunos = Aluno::where('nomealuno','LIKE',"%$busca%")->orderBy('id')->paginate(12);
        }else{
            $alunos = Aluno::orderBy('id','desc')->paginate(12);
        }





        return view('aluno.index',compact('alunos','turmas'));
    }



    public function create()
    {
        $tu = Turma::with('serie')->get();
        $turmas = [];
        $turmas[""] = "Selecione uma Turma";

        foreach($tu as $t){
            $turmas[$t->id] = $t->serie->nome." - ".$t->letra;
        }


        return view('aluno.create',compact('turmas'));
    }



    public function store(AlunoRequest $request)
    {

        if($request->ajax()){

            Aluno::create($request->all());
             return response()->json(['sucesso' => 'Aluno Cadastrado com Sucesso!']);

        }

    }


    public function edit($id)
    {
        $tu = Turma::with('serie')->get();
        $turmas = [];
        $turmas[""] = "Selecione uma Turma";

        foreach($tu as $t){
            $turmas[$t->id] = $t->serie->nome." - ".$t->letra;
        }

        $aluno = Aluno::find($id);

        return view('aluno.edit',compact('turmas','aluno'));

    }

    public function update($id,AlunoRequest $request)
    {
       $aluno = Aluno::find($id);
       $aluno->update($request->all());
        return response()->json(['sucesso' => 'Aluno Editado com Sucesso!']);

    }

    public function fotos(){

        $tu = Turma::with('serie')->get();
        $turmas = [];
        $turmas["todas"] = "Todas as Turmas";

        foreach($tu as $t){
            $turmas[$t->id] = $t->serie->nome." - ".$t->letra;
        }

         return view('aluno.upload',compact('turmas'));
    }



    public function uploadFotos(Request $request)
    {
        if($request->hasFile('fotos')) {
            $turma =  $request->input('turma');
            $mat = $request->input('matricula');
            $arquivo = $request->file('fotos');
            $local = public_path() . "/fotoaluno";



            if(Session::has('turma')){
                if($turma != Session::get('turma')){
                    Session::put('turma',$turma);
                    Session::forget('alunos');
                }else{
                    if( empty( Session::get('alunos',[] ) ) ){
                        Session::forget('alunos');
                    }
                }
            }else{
                Session::put('turma',$turma);
                Session::forget('alunos');
            }

            if($mat){
                Session::put('alunos',[ 0 => ['matricula' => $mat]]);
            }


            if($turma == 'todas' ){
                $nomearquivo = $arquivo->getClientOriginalName();

            }  else{

                if (Session::has('alunos'))
                {
                    $alunos = Session::get('alunos');
                }else{
                    $alunos = Aluno::where('turma_id',$turma)->select('matricula')->orderBy('numero')->get()->toArray();
                }

                $matricula = array_shift($alunos);
                Session::put('alunos',$alunos);

                $nomearquivo =  intval($matricula['matricula'] ).".".$arquivo->getClientOriginalExtension();

            }



            return $arquivo->move($local, $nomearquivo);
        }
    }


    public function removerfoto(Request $request)
    {
        $matricula = $request->input('matricula');
        \File::delete('fotoaluno/'.$matricula.'.jpg');
        return ['sucesso' => "Foto Excluída com Sucesso"];


    }

    public function importar()
    {
        return view('aluno.importar');
    }


    public function upload(Request $request)
    {

        if($request->hasFile('alunos')){

            $arquivo = $request->file('alunos');
            $arquivo->move('upload','alunos.txt');

            $arraydados = Aluno::geradados('upload/alunos.txt');
            \File::delete('upload/alunos.txt');

            Turma::getTurma();
            Aluno::truncate();
            $cont = 0;

            foreach($arraydados as $aluno){
                $turma = Turma::checkTurma( $aluno['turma'],$aluno['codcurso']);
                unset($aluno['turma']);
                unset($aluno['codcurso']);
                Aluno::create($aluno)->turma()->associate($turma)->save();
                $cont++;
            }

            if($cont){
                $msg =  ['sucesso' => "$cont alunos importados com sucesso"];
            }else{
                $msg = ['erro' => "Nenhum aluno pode ser importado"];
            }

           return response()->json($msg);


        }

    }
}
