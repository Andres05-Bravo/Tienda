@extends('template.layout')
@section('titulo')
    Dashboard | Tienda
@endsection

@section('contenido')
    <div class="container-fluid">
        <div class="row ">
            <div class="col-md-6">
                <div class="row">
                    @foreach ($productos as $item)
                        <div class="col-4 mb-4">
                            <div style="border: 1px solid #C3C3C3;padding: 12px;border-radius: 7px;">
                                <strong class="text-center">{{$item['nombre']}}</strong>
                                <img
                                    style="width: 100%;" 
                                    src="{{$item['img']}}"
                                >
                                <small>Lorem ipsum dolor Lorem ipsum dolor</small><br/>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-right font-weight-bold">$ {{number_format($item['valor'])}}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="javascript:crearFactura({{$item['id']}},'{{$item['nombre']}}',{{$item['valor']}});" class="text-right text-white font-weight-bold btn btn-sm btn-info">Comprar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-6 pb-5 pt-3" id="factura" style="border:1px solid #c3c3c3; display:none;">
                <form id="formPagar" method="POST" action="{{route('pagar')}}">
                @csrf
                <h4 class="text-right">Orden</h4>
                <strong>Factura</strong>
                <div class="row mt-5">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" value="{{Auth::user()->name}}" name="nombre" id="nombre" class="form-control" placeholder="Nombre" aria-describedby="nombre">
                            <small id="nombre"  class="text-muted">Digite su nombre para su factura</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="celular">Celular</label>
                            <input type="number" name="celular" id="celular" class="form-control" placeholder="Celular" aria-describedby="celular">
                            <small id="celular" class="text-muted">Digite un numero de celular para su factura</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="email">Correo</label>
                            <input type="email" value="{{Auth::user()->email}}" name="email" id="email" class="form-control" placeholder="Correo" aria-describedby="email">
                            <small id="email" class="text-muted">Digite un correo para su factura</small>
                        </div>
                    </div>
                </div>
               
                <div class="row mt-5 justify-content-center align-items-center">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6 text-right">
                                <strong style="font-size: 20px;">Producto:</strong>
                            </div>
                            <div class="col-6 text-left">
                                <small style="font-size: 20px;" id="nombre_producto"></small>
                            </div>
                            <div class="col-6 text-right">
                                <strong style="font-size: 20px;">Sub total:</strong>
                            </div>
                            <div class="col-6 text-left">
                                <small style="font-size: 20px;" id="valor_subTotal"></small>
                            </div>
                            <div class="col-6 text-right">
                                <strong style="font-size: 20px;">Total:</strong>
                            </div>
                            <div class="col-6 text-left">
                                <small style="font-size: 20px;" id="valor_producto"></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <a href="javascript:$('#formPagar').submit();" id="btn_pagar" class="btn btn-lg btn-info text-white font-weight-bold">Pagar con PlacetoPay</a>
                    </div>
                </div>
                </form>
            </div>
            <div class="col-md-6 pb-5 pt-3 text-center align-self-center" id="sinFactura">
                {{-- Sin factura --}}
                <img src="{{asset('assets/img/facturacion.png')}}" style="width: 30%">
                <h3 class="mt-4">¡Ahorra tiempo. Ahorra dinero!</h3>
            </div>
        </div>
    </div>
@endsection