<?php

namespace MusicRating\Middlewares;

class APIMiddleware
{
    public static function apply() {
        static::json();
    }
    
    private static function json() {
        header('Content-Type: application/json');
    }

}