<?php

namespace App\Http\Controllers\Cliente;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Order;
use Auth;
use Redirect;

class TiendaController extends Controller
{

    public $productos  = array(
        ["id" => 1, "nombre" => "Mandarina", "valor" => 3400, "img" => "http://frutasyverduraslosprimos.com/wp-content/uploads/2018/03/Mandarina-Coyote.jpg"],
        ["id" => 2, "nombre" => "Banana", "valor" => 3200, "img" => "https://mercaldas.vteximg.com.br/arquivos/ids/155600-400-400/14626.jpg?v=636885394137830000"],
        ["id" => 3, "nombre" => "Sandia", "valor" => 3800, "img" => "https://www.enpeso.com/blog/wp-content/uploads/2015/05/sandia-400x400.jpg"],
        ["id" => 4, "nombre" => "Aguacate", "valor" => 2300, "img" => "http://exoticfruitstore.com/wp-content/uploads/2019/03/4-400x400.jpg"],
        ["id" => 5, "nombre" => "Cerezas", "valor" => 2500, "img" => "http://assets.stickpng.com/thumbs/580b57fcd9996e24bc43c13c.png"],
        ["id" => 6, "nombre" => "Manzana", "valor" => 4500, "img" => "http://assets.stickpng.com/thumbs/580b57fbd9996e24bc43c116.png"],
    );

    public function index(){
        //Armar objecto temporal de productos 
        $productos = $this->productos;

        //hacemos return ala vista y le pasamos el objecto de productos
        return view('inicio.cliente.tienda',compact("productos"));
    }

    public function generarFactura(Request $request){
        $idProducto = $request->idProducto;
        $valor = $request->valor;
        $idCliente = Auth::user()->id;

        //Consultar si el usuario ya tiene una order Creada
        $order = Order::where('id_users', $idCliente)->where('status', "CREATED")->first();
        if(empty($order)){
            //Crear nueva order
            $newOrder = new Order;
            $newOrder->code = time();//Colocamos un identificador unico
            $newOrder->id_users = $idCliente;
            $newOrder->value = $valor;
            
            $newOrder->status = "CREATED";
            if($newOrder->save()){
                $response = array("code" => 200, "message" => "Order creada correctamente", "data" => $newOrder);
                return response()->json($response,$response["code"]); 
            }else{
                $response = array("code" => 500, "message" => "Hubo un erro al momento de generar esta factura");
                return response()->json($response,$response["code"]); 
            }
        }else{
            $order->value = $valor;
            $order->code = time();
            $order->update();
            //Ya tiene una order creada
            $response = array("code" => 200, "message" => "Ya tienes una Orden creada", "data" => $order);
            return response()->json($response,$response["code"]); 
        }
    }

    public function pagar(Request $form){
        $nombre = $form->nombre;
        $email = $form->email;
        $celular = $form->celular;
        $idCliente = Auth::user()->id;

        $order = Order::where('id_users', $idCliente)->where('status', "CREATED")->first();
        if(!empty($order)){
            //Si existe order actualizamos sus valores para la factura
            $order->customer_name = $nombre;
            $order->customer_email = $email;
            $order->customer_mobile = $celular;

            if($order->update()){
                //Construimos el objecto para placetopay
                $request = array(
                    "locale" => "es_CO",
                    "auth" => $this->auth(),
                    "buyer" => $this->buyer($nombre,$email,$celular),
                    "payment" => $this->payment($order->code,$order->value),
                    "returnUrl" => env('RETURNURL'),
                    "expiration" => $this->expiration(),
                    "ipAddress" => $_SERVER['REMOTE_ADDR'],
                    "userAgent" => "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36",
                    
                );

                $url = env('URL_MODO_PRUEBA').'/'."api/session/";
                
                // abrimos la sesión cURL
                $curl_ = curl_init();
                // definimos la URL a la que hacemos la petición
                curl_setopt($curl_, CURLOPT_URL, $url);
                // indicamos el tipo de petición: POST
                curl_setopt($curl_, CURLOPT_POST, 1);
                //para que la peticion no imprima el resultado como un echo comun, y podamos manipularlo
                curl_setopt($curl_, CURLOPT_RETURNTRANSFER, 1);
                // definimos cada uno de los parámetros
                curl_setopt($curl_, CURLOPT_POSTFIELDS, json_encode($request));
                //Agregar los encabezados del contenido
                curl_setopt($curl_, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'User-Agent: cUrl Testing'));
                //Ejecutamos la petición
                $result = curl_exec($curl_);
                $httpcode = curl_getinfo($curl_, CURLINFO_HTTP_CODE);
                // cerramos la sesión cURL
                curl_close($curl_);

                if($httpcode == 200){
                    $response = json_decode($result);
                    $order->reference = $response->requestId;
                    $order->status = "PENDING";
                    $order->processUrl = $response->processUrl;
                    if($order->update()){
                        return Redirect::to($response->processUrl);
                    }
                }

                
            }
            
        }
    }
}
