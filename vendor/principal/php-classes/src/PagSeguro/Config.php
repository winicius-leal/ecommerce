<?php

namespace Principal\PagSeguro;

    class Config {

        const SANDBOX = true;

        const SANDBOX_EMAIL = "winiciusleal@hotmail.com";
        const SANDBOX_TOKEN = "933C12C0BA3849F49C72B04D4B1A00D9";
        const SANDBOX_SESSIONS = "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions";
        const SANDBOX_URL_JS = "https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js";
        const SANDBOX_URL_TRANSACTION = "https://ws.sandbox.pagseguro.uol.com.br/v2/transactions";
        const SANDBOX_URL_NOTIFICATION = "https://ws.sandbox.pagseguro.uol.com.br/v3/transactions/notifications/";


        
        const PRODUCTION_EMAIL = "winiciusleal@hotmail.com";
        const PRODUCTION_TOKEN = "8fefa09c-1b34-4c62-a6a6-82ff01fa05ed23c3e6cf4e7a9b729881e04ad804960e6edc-663e-4728-b159-c5ada9589cb7";
        const PRODUCTION_SESSIONS = "https://ws.pagseguro.uol.com.br/v2/sessions";
        const PRODUCTION_URL_JS = "https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js";
        const PRODUCTION_URL_TRANSACTION = "https://ws.pagseguro.uol.com.br/v2/transactions";
        const PRODUCTION_URL_NOTIFICATION = "https://ws.pagseguro.uol.com.br/v3/transactions/notifications/";


        const MAX_INSTALLMENT_NO_INTEREST = 6; //NUMERO MAXIMO DE PARCELAMENTO SEM JUROS
        const MAX_INSTALLMENT = 10; //NUMERO MAXIMO DE PARCELAS QUE MEU SITE ACEITA

        const NOTIFICATION_URL = "http://sualoja.com.br/notificacao";
        
        public static function getAuthentication():Array{

            if(Config::SANDBOX === true){
                return Array(
                    "email"=>Config::SANDBOX_EMAIL,
                    "token"=>Config::SANDBOX_TOKEN
                );
            }else{
                return Array(
                    "email"=>Config::PRODUCTION_EMAIL,
                    "token"=>Config::PRODUCTION_TOKEN 
                );
            }
        }
        public static function getUrlSessions():string{
            if(Config::SANDBOX === true){
                return Config::SANDBOX_SESSIONS;
            }else{
                return Config::PRODUCTION_SESSIONS;
            }
        }

        public static function getUrlJS(){
            if(Config::SANDBOX === true){
                return Config::SANDBOX_URL_JS;
            }else{
                return Config::PRODUCTION_URL_JS;
            }
        }

        public function getUrlTransaction(){
            if(Config::SANDBOX === true){
                return Config::SANDBOX_URL_TRANSACTION;
            }else{
                return Config::PRODUCTION_URL_TRANSACTION;
            }
        }

        public function getNotificationTransactionURL(){
            if(Config::SANDBOX === true){
                return Config::SANDBOX_URL_NOTIFICATION;
            }else{
                return Config::PRODUCTION_URL_NOTIFICATION;
            }
        }
    } 

    


?>