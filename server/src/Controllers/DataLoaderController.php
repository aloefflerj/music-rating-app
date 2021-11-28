<?php

namespace MusicRating\Controllers;

use MusicRating\Models\DataLoaderModel;

class DataLoaderController
{

    /**
     * Undocumented variable
     *
     * @var DataLoaderModel
     */
    public static $dataLoader;

    public static function init()
    {
        /** @var self::$dataLoader */
        self::$dataLoader = new DataLoaderModel();
    }

    public static function populateDb()
    {
        return function ($req, $res, $body) {

            $data = self::callDataApi();
            
            $data = json_decode($data);

            self::$dataLoader->populateDb($data);

            if (self::$dataLoader->error()) {
                self::printError();
                return;
            }

            echo json_encode([
                'success' => true,
                'msg' => 'Dados adicionados às tabelas'
            ], JSON_PRETTY_PRINT);
        };
    }

    private static function callDataApi()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
        	CURLOPT_URL => "https://random-word-api.herokuapp.com/word?number=1000",
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_FOLLOWLOCATION => true,
        	CURLOPT_ENCODING => "",
        	CURLOPT_MAXREDIRS => 10,
        	CURLOPT_TIMEOUT => 30,
        	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        	CURLOPT_CUSTOMREQUEST => "GET",
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        	self::printError("cURL error: {$err}");
            return false;
        } 

        return $response;
    }


    private static function printError($err = false)
    {
        echo json_encode([
            'success' => false,
            'msg' => $err ? $err : self::$dataLoader->error()->getMessage()
        ], JSON_PRETTY_PRINT);
    }
}
