@extends('template')

@section('title','Realizar venta')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')

    <div class="container-fluid px-4">
            <h1 class="mt-4 text-center">Realizar venta</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Venta</a></li>
                <li class="breadcrumb-item active"></a>Crear Venta</li>
            </ol>

    </div>  
    <form action="{{route('ventas.store')}}" method="post">
        @csrf
        <div class="container mt-4">
            <div class="row gy-4">
                <!-------------- venta de productos ---------->
                <div class="col-md-8">
                    <div class="text-white bg-primary p-1 text-center">
                        Detalle de la venta
                    </div>
                    <div class="p-3 border border-3 border-primary">
                        <div class="row">

                            <!-- Productos -->
                            <div class="col-md-12 mb-2">
                                <select name="producto_id" id="producto_id" class="form-control selectpicker show-tick" data-live-search="true" title="Buscar producto" data-size='2'>
                                    @foreach($productos as $item)
                                        <option value="{{$item->id}}-{{$item->stock}}-{{$item->precio_venta}}">{{$item->codigo.'   '.$item->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="d-flex justify-content-end mb-4">
                                <div class="col-md-6 mb-2">
                                    <div class="row">
                                        <label for="stock" class="form-label col-sm-4">En stock:</label>
                                        <div class="col-sm-8">
                                            <input disabled type="text" class="form-control" id="stock">
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                            

                            <div class="col-md-4 mb-2">
                                <label for="cantidad" class="form-label">Cantidad:</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control">
                            </div>

                             
                            <div class="col-md-4 mb-2">
                                <label for="precio_venta" class="form-label">Precio venta:</label>
                                <input disabled type="number" name="precio_venta" id="precio_venta" class="form-control" step="0.1">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label for="descuento" class="form-label">Descuento:</label>
                                <input type="number" name="descuento" id="descuento" class="form-control" step="0.1">
                            </div>
                            

                            <div class="col-md-12 mb-2 mt-2 text-end">
                                <button id="btn_agregar" class="btn btn-primary" type="button">Agregar</button>
                            </div>
                            
                            <div class="col-md-12">

                            </div>
                            
                            <!-- Tabla para el detalle de la venta -->
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tabla_detalle" class="table table-hover">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio venta</th>
                                                <th>Descuento</th>
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
                            <!-- Cancelar venta -->
                            <div class="col-md-12 mb-2">
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal" id="cancelar">
                                    Cancelar venta
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
                            <!-- Cliente -->
                            <div class="col-md-12 mb-2">
                                <label for="cliente_id" class="form-label">Cliente:</label>
                                <select name="cliente_id" id="cliente_id" class="form-control selectpicker show-tick" data-live-search="true" title="selecciona" data-size='2'>
                                    @foreach($clientes as $item)
                                        <option value="{{$item->id}}">{{$item->persona->razon_social}}</option>
                                    @endforeach
                                    @error('cliente_id')
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

                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success" id="guardar">Guardar</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <!-- Modal para cancelar la venta-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Seeguro que quieres cancelar la venta
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button id="btnCancelarVenta" type="button" class="btn btn-primary" data-bs-dismiss="modal">Confirmar</button>
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

        $('#producto_id').change(mostrarValores);

        $('#btn_agregar').click(function(){
            agregarProducto();
        });
        $('#btnCancelarVenta').click(function(){
            cancelarVenta();
        })

        disableButtons();
        $('#impuesto').val(impuesto + '%');

    
    });

    // Variables
    let cont = 0;
    let subtotal = [];
    let sumas = 0;
    let igv = 0;
    let total = 0;

    //constantes
    const impuesto = 18;

    function mostrarValores(){

        let dataProducto =  document.getElementById('producto_id').value.split('-');
        $('#stock').val(dataProducto[1]);
        $('#precio_venta').val(dataProducto[2])
    }

    function agregarProducto(){

        let dataProducto =  document.getElementById('producto_id').value.split('-');

        let idProducto = dataProducto[0];
        let nameProducto = $('#producto_id option:selected').text();
        let cantidad = $('#cantidad').val();
        let precioVenta = $('#precio_venta').val();
        let descuento = $('#descuento').val();
        let stock = $('#stock').val();

        if(descuento == ''){
            descuento = 0;
        }

        //validaciones para los campos vacios
        if(idProducto!='' && cantidad!=''){

            // Para que los valores ingresados sean correctos
            if(parseInt(cantidad) > 0 && cantidad%1 ==0 && parseFloat(descuento) >= 0){

                // Para que cantidad no supere el stock
                if(parseFloat(cantidad) <= parseFloat(stock)){
                    
                    // Calcular valores
                    subtotal[cont] = round(cantidad * precioVenta - descuento);
                    sumas+=subtotal[cont];
                    igv = round(sumas/100 *  impuesto); 
                    total = round(sumas + igv);



                    let fila = '<tr id="fila' + cont + '">'+
                    '<th>'+ (cont + 1) +'</th>' +
                    '<th><input type="hidden" name="arrayidproducto[]" value="'+ idProducto +'">'+ nameProducto +'</th>' +
                    '<th><input type="hidden" name="arraycantidad[]" value="'+ cantidad +'">'+ cantidad +'</th>' +
                    '<th><input type="hidden" name="arrayprecioventa[]" value="'+ precioVenta +'">'+ precioVenta +'</th>' +
                    '<th><input type="hidden" name="arraydescuento[]" value="'+ descuento +'">'+ descuento +'</th>' +
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

                    showModal('Cantidad incorrecta')
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

    function cancelarVenta(){

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


    function limpiarCampos(){

    let select = $('#producto_id');
    select.selectpicker();
    select.selectpicker('val','');
    $('#cantidad').val('');
    $('#precio_venta').val('');
    $('#descuento').val('');
    $('#stock').val('');

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