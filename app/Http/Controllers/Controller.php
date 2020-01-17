<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function auth(){
        $nonce = time();
        $seed = Date("c");
        $tranKey = base64_encode(hash('sha1', $nonce . $seed . "024h1IlD" , true));

        $auth = array(
            "login" =>"6dd490faf9cb87a9862245da41170ff2",
            "tranKey" => $tranKey,
            "nonce" => base64_encode($nonce),
            "seed" => $seed,
        );

        return $auth;
    }

    protected function buyer($nombre,$email,$celular){
        $buyer = array(
            "name" => $nombre,
            "surname" => $nombre,
            "email" => $email,
            "mobile" => $celular
        );

        return $buyer;
    }

    protected function amount($valor){
        $amount = array(
            "currency" => "COP",
            "total" => $valor
        );
        
        return $amount;
    }   

    protected function payment($reference,$valor){
        $payment = array(
            "reference" => $reference,
            "description" => "Compra de fruta",
            "amount" => $this->amount($valor),
        );

        return $payment;
    }

    protected function expiration(){
        //Sacar la fecha de expiration
        $fecha = date('c');
        $nuevafecha = strtotime ( '+10 minute' , strtotime ( $fecha ) ) ;
        $expiration = date ( 'c' , $nuevafecha );

        return $expiration;
    }

        
}
