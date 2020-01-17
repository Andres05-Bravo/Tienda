<nav class="mb-4 navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand font-bold" href="#">Tienda | Pruebas</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#btnMenu" aria-controls="btnMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="btnMenu">
        <ul class="navbar-nav ml-auto">
            @if(Auth::user())
                <li class="nav-item">
                    <a style="font-size: 20px;text-transform: uppercase;" class="nav-link" href="#"><i class="fa fa-gear"></i>{{Auth::user()->name}}</a>        
                </li>
                <li class="nav-item {{ Request::is('administrador') ? 'active' : '' }}">
                    <a style="font-size: 20px;" class="nav-link" href="{{route('tiendaIndex')}}"><i class="fa fa-envelope"></i> Tienda <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item {{ Request::is('administrador/mis/order') ? 'active' : '' }}">
                    <a style="font-size: 20px;" class="nav-link" href="{{route('orders')}}"><i class="fa fa-gear"></i>Mis Pedidos</a>        
                </li>
                <li class="nav-item">
                    <a style="font-size: 20px;" class="nav-link" href="{{route("getLogout")}}"><i class="fa fa-gear"></i>Cerrar Sesión</a>        
                </li>
            @endif
            
        </ul>
    </div>
</nav>