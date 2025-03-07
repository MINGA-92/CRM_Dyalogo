@extends('layouts.admin')

@section('title', 'Huéspedes')

@section('content')
<div id="mostrar_loading"></div>
<div class="box box-primary">  
    <div class="box-header"></div>
    <div class="box-body">
        <div class="col-md-3">

            <div class="col-md-12">
                <form id="search-huesped" action="{{ route('huesped.search') }}" method="GET">
                    <div class="input-group">
                        <input type="text" id="texto" name="texto" class="form-control" placeholder="Buscar">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
            
            <div class="col-md-12" style="margin-top: 30px">
                <div class="list-group list-huesped" style="max-height: 620px;
                                        margin-bottom: 10px;
                                        overflow: auto;   
                                        -webkit-overflow-scrolling: touch;">
                    
                    @foreach ($huespedes as $huesped)
                        <a href="#" class="list-group-item item-huesped">
                            <input type="text" class="huesped_id" style="display: none" value="{{ $huesped->id }}">
                            <h4 class="list-group-item-heading"><b>{{ $huesped->nombre }}</b></h4>
                            <p class="list-group-item-text text-muted">Teléfono {{ $huesped->telefono1 }}</p>
                        </a>
                    @endforeach
                        
                </div>
            </div>
            
        </div>

        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-group">
                        <button type="button" id="create" class="btn btn-default" title="Crear nuevo huésped"><i class="fa fa-plus"></i></button>
                        
                        <button type="button" id="store" class="btn btn-default pull-right" title="Registrar nuevo huésped"><i class="fa fa-save"></i></button>
                        <button type="button" id="update" class="btn btn-default pull-right" style="display: none;" title="Actualizar huésped"> <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- Accordion -->
                    <div class="box box-solid">
                        <div class="box-header with-border"></div>
                        
                        <div class="box-body" style="padding-left: 0px;">
                            <div id="accordion">
                                
                                @include('huesped.secciones.huesped')

                                @include('huesped.secciones.cantidadesAutorizadas')

                                @include('huesped.secciones.passwordPolicy')
                                
                                @include('huesped.secciones.mallaTurnos')

                                @include('huesped.secciones.pausas')

                                @include('huesped.secciones.festivos')

                                @include('huesped.secciones.canal')

                                @include('huesped.secciones.notificaciones')
                                
                                @include('huesped.secciones.mensajeActual')

                                @include('huesped.secciones.usuarios')

                                @include('huesped.modals.pruebaNotificacionesMailSmsModal')
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    <div class="box-footer">
        Footer
    </div>
</div>

@endsection

@section('scripts')
    {{-- <script src="{{ asset('js/scripts/forms.js') }}"></script> --}}
    <script src="{{ asset('js/scripts/huesped.js') }}"></script>
@endsection
