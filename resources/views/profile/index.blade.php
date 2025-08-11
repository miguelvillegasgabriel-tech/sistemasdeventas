@extends('template')

@section('title', 'perfil')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.3/dist/sweetalert2.all.min.js"></script>

@endpush

@section('content')





<div class="container-fluid">
    <h1 class="mt-4 text-center">Configurar perfil</h1>
    <div class="container-fluid card mt-4">
        <div class="mt-4">
            @if($errors->any())
            @foreach ($errors->all() as $item)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{$item}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endforeach
            @endif   
        </div>
        <form class="card-body" action="{{route('profile.update',['profile' => $user])}}" method="POST">
        @method('PATCH')
        @csrf
            <div class="row mb-4">
                <div class="col-sm-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-square-check"></i></span>
                        <input disabled type="text" class="form-control" value="Nombres">
                    </div>
                </div>
                <div class="col-sm-8">
                    <input type="text" name="name" id="name" class="form-control" value="{{old('name', $user->name)}}">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-sm-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-square-check"></i></span>
                        <input disabled type="text" class="form-control" value="Email">
                    </div>
                </div>
                <div class="col-sm-8">
                    <input type="email" name="email" id="email" class="form-control" value="{{old('email', $user->email)}}">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-sm-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-square-check"></i></span>
                        <input disabled type="text" class="form-control" value="Contraseña">
                    </div>
                </div>
                <div class="col-sm-8">
                    <input type="password" name="password" id="password" class="form-control">
                </div>
            </div>


            <div class="col text-center">
                <input class="btn btn-success"type="submit" value="Guardar cambios">
            </div>

        </form>
    </div>
</div>

@endsection

@push('js')


@endpush