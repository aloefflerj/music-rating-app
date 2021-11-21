<?php

namespace MusicRating\Controllers;

use MusicRating\Models\UsersModel;

class ArtistController
{

    /**
     * Undocumented variable
     *
     * @var UsersModel
     */
    public static $users;

    public static $userController;

    public static function init()
    {
        /** @var self::$artists */
        self::$users = new UsersModel();
    }

    private static function printError()
    {
        echo json_encode([
            'success' => false,
            'msg' => self::$artists->error()->getMessage()
        ], JSON_PRETTY_PRINT);
    }
}
