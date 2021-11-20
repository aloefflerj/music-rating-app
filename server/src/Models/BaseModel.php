<?php

namespace MusicRating\Models;

class BaseModel
{
    protected $error;

    /*
     * =======================
     * || FIELD VALIDATIONS || =====================================>
     * =======================
     */

    // id
    public function validateId(&$id)
    {
        if (empty($id) || !filter_var($id, FILTER_VALIDATE_INT)) {
            $this->error = new \Exception('Insira um valor inteiro válido');
            return false;
        }

        return true;
    }

    public function error()
    {
        return $this->error ?? false;
    }

}