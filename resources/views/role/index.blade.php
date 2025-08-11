@extends('template')


@section('title', 'roles')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.3/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.3/dist/sweetalert2.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush


@section('content')



 <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Roles</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
                <li class="breadcrumb-item active"></a>Roles</li>
                

            </ol>
            <div class="mb-4">
                <a href="{{ route('roles.create')}}"><button type="button" class="btn btn-primary">Añadir nuevo rol</button></a>
            </div>
            
            <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Tabla Roles
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Rol</th>
                                            <th>Acciones</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($roles as $item)
                                        <tr>
                                            <td>{{$item->name}}</td>
                                            
                                            <td>
                                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                    <form action="{{route('roles.edit',['role' => $item])}}" method="get">
                                                        <button type="submit" class="btn btn-warning">Editar</button>
                                                    </form>
                                                    
                                                   
                                                    <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmodal-{{$item->id}}">Eliminar</button>
                                                   
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="confirmodal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de Confirmacion</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                       ¿Seguro que quieres eliminar el rol?
                                                                                                     
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        
                                                        <form action="{{route('roles.destroy',['role'=>$item->id])}}" method="post">
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