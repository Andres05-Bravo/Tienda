@extends('template.layout')
@section('titulo')
    Login | Tienda
@endsection

@section('contenido')
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-4" style="margin-top: 60px;">
                @if(Session::has('message_success') || Session::has('message_error'))
                    <div class="alert {{Session::has('message_error') ? 'alert-danger' : 'alert-success'}} alert-dismissible fade show" role="alert">
                        <strong>Correcto!</strong> {{Session::get('message_success') ? Session::get('message_success') : Session::get('message_error')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @elseif(Session::has('errors'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Hay algunos errores!</strong><br/>
                        @foreach ($errors->all() as $message)
                            {{$message}}<br/>
                        @endforeach
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <h2 class="text-center mb-4">Iniciar Sesión</h2>
                <div style="background: #f8f9fa;padding: 17px;">
                    <form method="POST" action="{{route('loginPost')}}">
                        @csrf
                        <div>
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
                                <input type="submit" class="btn btn-success text-white font-weight-bold" value="Iniciar Sesión">
                            </div>
                            <div class="col-md-6 text-center">
                                <a href="{{route('registro')}}" class="btn btn-info text-white font-weight-bold">Registrate</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection