@extends('template')

@section('title','Crear compra')

@push('css')
<style>
    #descripcion{
        resize:none;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endpush


@section('content')
    <div class="container-fluid px-4">
            <h1 class="mt-4 text-center">Crear compra</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('compras.index') }}">Compra</a></li>
                <li class="breadcrumb-item active"></a>Crear Compra</li>
            </ol>

    </div>  

      
    <form action="{{route('compras.store')}}" method="post">
        @csrf
        <div class="container mt-4">
            <div class="row gy-4">
                <!-------------- compra productos ---------->
                <div class="col-md-8">
                    <div class="text-white bg-primary p-1 text-center">
                        Detalle de la compra
                    </div>
                    <div class="p-3 border border-3 border-primary">
                        <div class="row">

                            <div class="col-md-12 mb-2">
                                <select name="producto_id" id="producto_id" class="form-control selectpicker show-tick" data-live-search="true" title="Buscar producto" data-size='2'>
                                    @foreach($productos as $item)
                                        <option value="{{$item->id}}">{{$item->codigo.'   '.$item->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="cantidad" class="form-label">Cantidad:</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label for="precio_compra" class="form-label">Precio compra:</label>
                                <input type="number" name="precio_compra" id="precio_compra" class="form-control" step="0.1">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label for="precio_venta" class="form-label">Precio venta:</label>
                                <input type="number" name="precio_venta" id="precio_venta" class="form-control" step="0.1">
                            </div>

                            <div class="col-md-12 mb-2 mt-2 text-end">
                                <button id="btn_agregar" class="btn btn-primary" type="button">Agregar</button>
                            </div>
                            
                            <div class="col-md-12">

                            </div>
                            

                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tabla_detalle" class="table table-hover">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio compra</th>
                                                <th>Precio venta</th>
                                                <th>Subtotal</th>
                                                <th></th>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th colspan="4">Sumas</th>
                                                <th colspan="2"><span id="sumas">0</span></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th colspan="4">IGV %</th>
                                                <th colspan="2"><span id="igv">0</span></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th colspan="4">Total</th>
                                                <th colspan="2"><input type="hidden" name="total" value="0" id="inputTotal"><span id="total">0</span></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" id="cancelar">
                                    Cancelar compra
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-white bg-success p-1 text-center">
                        Datos generales
                    </div>
                    <div class="p-3 border border-3 border-success">
                        <div class="row">
                            <!-- proveedores -->
                            <div class="col-md-12 mb-2">
                                <label for="proveedore_id" class="form-label">Proveedor:</label>
                                <select name="proveedore_id" id="proveedore_id" class="form-control selectpicker show-tick" data-live-search="true" title="selecciona" data-size='2'>
                                    @foreach($proveedores as $item)
                                        <option value="{{$item->id}}">{{$item->persona->razon_social}}</option>
                                    @endforeach
                                    @error('proveedore_id')
                                        <small class="text-danger">{{ '*'.$message}}</small>

                                    @enderror
                                </select>
                            </div>
                             <!-- comprobante -->
                             <div class="col-md-12 mb-2">
                                <label for="comprobante_id" class="form-label">Comprobante:</label>
                                <select name="comprobante_id" id="comprobante_id" class="form-control selectpicker show-tick" data-live-search="true" title="selecciona" data-size='2'>
                                    @foreach($comprobantes as $item)
                                    <option value="{{ $item->id }}">{{ $item->tipo_comprobante }}</option>
                                    @endforeach
                                </select>
                                @error('tipo_comprobante')
                                    <small class="text-danger">{{ '*'.$message }}</small>
                                @enderror
                                </select>
                            </div>
                             <!-- numero_comprobante -->
                             <div class="col-md-12 mb-2">
                                <label for="numero_comprobante" class="form-label">Numero de comprobante:</label>
                                <input requerid type="text" name="numero_comprobante" id="numero_comprobante" class="form-control">
                                @error('numero_comprobante')
                                        
                                    <small class="text-danger">{{ '*'.$message}}</small>

                                 @enderror
                            </div>
                             <!-- impuesto -->
                             <div class="col-md-6 mb-2">
                                <label for="impuesto" class="form-label">Impuesto:</label>
                                <input readonly type="text" name="impuesto" id="impuesto" class="form-control border-success">
                                    @error('impuesto')
                                      
                                        <small class="text-danger">{{ '*'.$message}}</small>

                                    @enderror
                            </div>
                            <!-- fecha -->
                            <div class="col-md-6 mb-2">
                                <label for="fecha" class="form-label">Fecha:</label>
                                <input readonly type="date" name="fecha" id="fecha" class="form-control border-success" value="<?php echo date("Y-m-d")?>">
                                
                                <?php  
                                use Carbon\Carbon;
                                $fecha_hora = Carbon::now()->toDateTimeString();
                                ?>
                                <input type="hidden" name="fecha_hora" value="{{$fecha_hora}}">
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success" id="guardar">Guardar</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <!-- Modal para cancelar la compra-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Seeguro que quieres cancelar la compra
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button id="btnCancelarCompra" type="button" class="btn btn-primary" data-bs-dismiss="modal">Confirmar</button>
            </div>
            </div>
        </div>
        </div>
    </form>
  
   
@endsection


@push('js')

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<script>
    $(document).ready(function(){

        $('#btn_agregar').click(function(){
            agregarProducto();
        });

        $('#btnCancelarCompra').click(function(){
            cancelarCompra();
        });

        disableButtons();

        $('#impuesto').val(impuesto + '%');
    });


    let cont = 0;
    let subtotal =[];
    let sumas =0;
    let igv=0;
    let total=0;

    const impuesto = 18;

    function cancelarCompra(){

        $('#tabla_detalle > tbody').empty();

        let fila = '<tr>'+
            '<th></th>'+
            '<th></th>'+
            '<th></th>'+
            '<th></th>'+
            '<th></th>'+
            '<th></th>'+
            '<th></th>'+
        '</tr>';          

        $('#tabla_detalle').append(fila);

        //reiniciar los valores de las variables
        cont = 0;
        subtotal =[];
        sumas =0;
        igv=0;
        total=0;

        //mostrar los campos calculados
        $('#sumas').html(sumas);
        $('#igv').html(igv);
        $('#total').html(total);
        $('#impuesto').val(impuesto + '%');
        $('#inputTotal').val(total);



        limpiarCampos();
        disableButtons();

    }

    function disableButtons(){
        if(total==0){
            $('#guardar').hide();
            $('#cancelar').hide();
        }else{
            $('#guardar').show();
            $('#cancelar').show();
        }
    }


    function agregarProducto(){
        let idProducto = $('#producto_id').val();
        let nameProducto = ($('#producto_id option:selected').text()).split('   ')[1];
        let cantidad = $('#cantidad').val();
        let precioCompra =  $('#precio_compra').val();
        let precioVenta = $('#precio_venta').val();

        //validaciones para los campos vacios
        if(nameProducto!='' && cantidad!='' && precioCompra!='' && precioVenta!=''){

            if(parseInt(cantidad) > 0 && cantidad%1 ==0 && parseFloat(precioCompra) > 0 && parseFloat(precioVenta) > 0){


                if(parseFloat(precioVenta) > parseFloat(precioCompra)){

                    subtotal[cont] = round(cantidad * precioCompra);
                    sumas+=subtotal[cont];
                    igv = round(sumas/100 *  impuesto);
                    total = round(sumas + igv);



                    let fila = '<tr id="fila' + cont + '">'+
                    '<th>'+ (cont + 1) +'</th>' +
                    '<th><input type="hidden" name="arrayidproducto[]" value="'+ idProducto +'">'+ nameProducto +'</th>' +
                    '<th><input type="hidden" name="arraycantidad[]" value="'+ cantidad +'">'+ cantidad +'</th>' +
                    '<th><input type="hidden" name="arraypreciocompra[]" value="'+ precioCompra +'">'+ precioCompra +'</th>' +
                    '<th><input type="hidden" name="arrayprecioventa[]" value="'+ precioVenta +'">'+ precioVenta +'</th>' +
                    '<th>'+ subtotal[cont] +'</th>' +
                    '<th><button type="button" class="btn btn-danger" onClick="eliminarProducto('+ cont +')" ><i class="fa-solid fa-trash"></i></button></th>' +
                    '</tr>';


                    $('#tabla_detalle').append(fila);
                    limpiarCampos();
                    cont++;
                    disableButtons();


                    //mostrar los datos calculados
                    $('#sumas').html(sumas);
                    $('#igv').html(igv);
                    $('#total').html(total);
                    $('#impuesto').val(igv);
                    $('#inputTotal').val(total);



                }else{

                    showModal('Precio de compra incorrecto')
                }
                

            }else{

                showModal('Valores incorrectos');
            
            }

        }else{

            showModal('Faltan campos por rellenar');
        }
    }

        function eliminarProducto(indice){

            //calcular valores
            sumas-= round(subtotal[indice]);
            igv = round(sumas / 100 * impuesto);
            total = round(sumas + igv);

            //mostrar los campos calculados
            $('#sumas').html(sumas);
            $('#igv').html(igv);
            $('#total').html(total);
            $('#impuesto').val(igv);
            $('#inputTotal').val(total);


            //eliminar la fila de la tabla 
            $('#fila' + indice).remove();
            disableButtons();


        }

        function limpiarCampos(){

            let select = $('#producto_id');
            select.selectpicker();
            select.selectpicker('val','');
            $('#cantidad').val('');
            $('#precio_compra').val('');
            $('#precio_venta').val('');

        }

        function round(num, decimales = 2) {

            const factor = Math.pow(10, decimales);
            return Math.round(num * factor) / factor;

        }

        function showModal(message, icon='error'){

            const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
            });
            Toast.fire({
            icon: icon,
            title: message
            });

        }


      
</script>
@endpush