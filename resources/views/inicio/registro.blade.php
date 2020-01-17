@extends('template.layout')
@section('titulo')
    Registro | Tienda
@endsection

@section('contenido')
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-4" style="margin-top: 60px;">
                <h2 class="text-center mb-4">Registrate</h2>
                <div style="background: #f8f9fa;padding: 17px;">
                    <form method="POST" action="{{route('registroPost')}}">
                        @csrf
                        <div>
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre">
                            </div>
                            <div class="form-group">
                                <label for="email">Correo</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Correo">
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña">
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6 text-center">
                                <a href="{{route('login')}}" class="btn btn-info text-white font-weight-bold">Ir Atras</a>
                            </div>
                            <div class="col-md-6 text-center">
                                <input type="submit" class="btn btn-success text-white font-weight-bold" value="Registrarme">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection