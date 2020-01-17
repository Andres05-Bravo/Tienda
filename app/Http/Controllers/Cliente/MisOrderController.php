<?php

namespace App\Http\Controllers\Cliente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Order;
use Auth;
use Redirect;

class MisOrderController extends Controller
{
    protected function consultarOrder($requestId){
        $auth = $this->auth();
        $request = array(
            "auth" => $auth
        );
        $url = env('URL_MODO_PRUEBA').'/'."api/session/".$requestId;

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
        // cerramos la sesión cURL
        curl_close($curl_);

        return $response = json_decode($result); 
    }

    public function orders(){
        $idUser = Auth::user()->id;
        $order = Order::where('id_users', $idUser)->where('status', 'PENDING')->first();
        if(!empty($order)){
            $response = $this->consultarOrder($order->reference);
            if($response){
                if($response->status->status == "APPROVED"){
                    $order->status = "PAYED";
                }else{
                    $order->status = $response->status->status;
                }
                
                $order->update();
            }
        }

        //Lista de orders por usuario autenticado
        $orders = Order::Where('id_users', $idUser)->where('status', '<>', 'CREATED')->get();
        return view('inicio.cliente.orders',compact('orders'));
    }

    public function reintentarPago($id){
        $idUser = Auth::user()->id;
        $order = Order::where('id_users', $idUser)->where('id', $id)->first();
        if(!empty($order)){

            $nombre = $order->customer_name;
            $email = $order->customer_email;
            $celular = $order->customer_mobile;

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

        }else{
            return back();
        }
    }
}
