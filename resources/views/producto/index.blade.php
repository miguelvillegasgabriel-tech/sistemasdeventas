@extends('template')

@section('title','productos')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.3/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.3/dist/sweetalert2.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">


@endpush

@section('content')





    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Productos</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
                <li class="breadcrumb-item active"></a>Productos</li>
                

            </ol>
            <div class="mb-4">
                <a href="{{ route('productos.create')}}"><button type="button" class="btn btn-primary">Añadir nuevo producto</button></a>
            </div>
            <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table"></i>
                                Tabla Categorias
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-striped fs-6">
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Nombre</th>
                                            <th>Descripcion</th>
                                            <th>Marca</th>
                                            <th>Presentacion</th>
                                            <th>Categorias</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productos as $item)
                                        <tr>
                                            <td>{{$item->codigo}}</td>
                                            <td>{{$item->nombre}}</td>
                                            <td>{{$item->descripcion}}</td>
                                            <td>{{$item->marca->caracteristica->nombre}}</td>
                                            <td>{{$item->presentacione->caracteristica->nombre}}</td>
                                            <td>
                                                @foreach ($item->categoria as $category)
                                                <div class="container">
                                                    <div class="row">
                                                        <span class="m-1 rounded-pill p-1 bg-secondary text-white text-center">{{$category->caracteristica->nombre}}</span>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if($item->estado==1)
                                                <span class="fw-bolder rounded p-1 bg-success text-white">Activo</span>
                                                @else
                                                <span class="fw-bolder rounded p-1 bg-danger text-white">Eliminado</span>
                                                @endif

                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                                    <form action="{{route('productos.edit',['producto' => $item])}}" method="get">
                                                        <button type="submit" class="btn btn-warning">Editar</button>
                                                    </form>
                                                    
                                                    <button type="submit" class="btn btn-success  btn-sm" data-bs-toggle="modal" data-bs-target="#vermodal-{{$item->id}}">Ver Producto</button>
                                                    @if($item->estado ==1)
                                                        <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmodal-{{$item->id}}">Eliminar</button>
                                                    @else
                                                        <button type="submit" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#confirmodal-{{$item->id}}">Restaurar</button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>


                                        <!-- Modal ver producto-->
                                        <div class="modal fade " id="vermodal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <label for="" ><span class="fw-bolder">Descripcion: </span>{{$item->descripcion}}</label>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="" ><span class="fw-bolder">Fecha de Vencimiento: </span>{{$item->fecha_vencimiento =='' ? 'No tiene': $item->fecha_vencimiento}}</label>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="" ><span class="fw-bolder">Stock: </span>{{$item->stock}}</label>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="" class="fw-bolder">Imagen: </label>
                                                    <div>
                                                        @if($item->img_path != null)
                                                        <img src="{{ asset('storage/productos/'. $item->img_path) }}" alt="{{ $item->nombre }}" class="img-fluid .img-thumbnail border border-4 rounded">                                                 
                                                        @else
                                                            <img src="" alt="{{ $item->nombre }}">
                                                        @endif
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                               
                                            </div>
                                            </div>
                                        </div>
                                        </div>


                                        <!--modal de confirmacion -->

                                        <div class="modal fade" id="confirmodal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de Confirmacion</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ $item->estado == 1 ? '¿Seguro que quieres eliminar el producto?' : '¿Seguro que quieres restaurar el producto?'}}                                                   </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        
                                                        <form action="{{route('productos.destroy',['producto'=>$item->id])}}" method="post">
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

@endsection


@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>

@endpush