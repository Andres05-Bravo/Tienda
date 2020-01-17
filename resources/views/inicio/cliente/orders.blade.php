@extends('template.layout')
@section('titulo')
    Mis Orden | Tienda
@endsection

@section('contenido')
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8">
                <h4 class="mb-4">Mis Pedidos</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Codigo</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $cnt = 1;
                        @endphp
                        @foreach ($orders as $item)
                            <tr>
                                <th scope="row">{{$cnt}}</th>
                                <td>{{$item->code}}</td>
                                <td>{{$item->customer_name}}</td>
                                <td>{{number_format($item->value)}}</td>
                                @if ($item->status == "REJECTED")
                                    <td>
                                        <a href="{{route('reintentarPago',[$item->id])}}" class="font-weight-bold btn btn-info text-white">Rechazada | Reintentar Pago</a>
                                    </td>
                                @elseif($item->status == "PAYED")
                                    <td>
                                        <a href="#" class="font-weight-bold btn btn-success text-white">Pagada</a>
                                    </td>
                                @elseif($item->status == "PENDING")
                                    <td>
                                        <a href="{{$item->processUrl}}" class="font-weight-bold btn btn-warning text-white">Pendiente | Ir A Pagar</a>
                                    </td>
                                @endif
                            </tr>
                            @php
                                $cnt++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection