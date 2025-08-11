@extends('template')

@section('title','presentaciones')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.3/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.3/dist/sweetalert2.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">

@endpush

@section('content')




    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Presentaciones</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
                <li class="breadcrumb-item active"></a>Presentaciones</li>
                

            </ol>
            <div class="mb-4">
                <a href="{{ route('presentaciones.create')}}"><button type="button" class="btn btn-primary">Añadir nuevo registro</button></a>
            </div>
            
            <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabla Marca
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($presentaciones as $presentacione)
                                            <tr>
                                                <td>{{$presentacione->caracteristica->nombre}}</td>
                                         
                                                <td>{{$presentacione->caracteristica->descripcion}}</td>

                                                <td>
                                                    @if($presentacione->caracteristica->estado == 1)
                                                        <span class="fw-bolder p-1 rounded bg-success text-white">Activo</span>

                                                    @else
                                                    <span class="fw-bolder p-1 rounded bg-danger text-white">Eliminado</span>
                                                    @endif
                                                </td>

                                                <td>
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                                    <form action="{{route('presentaciones.edit',['presentacione'=>$presentacione])}}" method="get">
                                                      
                                                        <button type="submit" class="btn btn-warning">Editar</button>

                                                    </form>
                                                    @if ($presentacione->caracteristica->estado == 1)
                                                        
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$presentacione->id}}">Eliminar</button>

                                                    @else
                                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$presentacione->id}}">Restaurar</button>

                                                    @endif


                                                </div>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="confirmModal-{{$presentacione->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de Confirmacion</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ $presentacione->caracteristica->estado == 1 ? '¿Seguro que quieres eliminar la presentacion?' : '¿Seguro que quieres restaurar la presentacion?'}}                                                   </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        
                                                        <form action="{{route('presentaciones.destroy',['presentacione'=>$presentacione->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger">Confirmar</button>
                                                        </form>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
    </div>

@endsection

@push('js')

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>

@endpush