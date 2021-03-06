@extends('template.principal')

@section('breadcrumb')
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i>
            Tipos de Ocorrência
            <a href="{!! route('tipos.index') !!}" class="btn btn-default">← Voltar</a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Inicial</a></li>
            <li>Tipos Ocorrência</li>
            <li class="active">Editar</li>
        </ol>
    </section>


@endsection

@section('conteudo')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info ">
                <div class="box-header">
                    <h3 class="box-title">Editar Tipos de Ocorrência</h3>

                </div><!-- /.box-header -->
                <div class="box-footer text-black">
                    {!! Form::model($tipo,['route' => ['tipos.update',$tipo->id],'id' => 'form','method' => 'PUT'] ) !!}
                    <div class="row">
                        <div class="col-md-8">
                            {!! Form::label('descricao','Descrição:') !!}
                            {!! Form::text('descricao',null,['class' => 'form-control input-lg','id' => 'descricao']) !!}
                        </div>

                        <div class="col-md-4">
                            {!! Form::label('tipo','Tipo') !!}
                            {!! Form::select('tipo',['' => 'Selecione o Tipo de Ocorrência','Positiva' => 'Positiva', 'Negativa' => 'Negativa','Mensagens' => 'Mensagens'],null,['class' => 'form-control input-lg','id' => 'tipo']) !!}
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">

                            <button type="submit" id="salvar" class="botao pull-right btn btn-info btn-lg"><i class="fa fa-save"></i> Salvar</button>

                        </div>
                    </div>
                    {!! Form::close() !!}
                </div><!-- /.box-footer -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->


@endsection

@section('script')

    {!! Html::script('js/acoes_formulario.js') !!}

@endsection



