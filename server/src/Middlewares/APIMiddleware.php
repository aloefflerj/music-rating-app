<?php

namespace MusicRating\Middlewares;

class APIMiddleware
{
    public static function apply() {
        static::json();
    }
    
    private static function json() {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: http://localhost");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: Authorization,Origin,X-Requested-With,Content-Type,Range");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    }

}