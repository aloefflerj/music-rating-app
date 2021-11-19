<?php

namespace MusicRating\Models;

class BaseModel
{

    /*
     * =======================
     * || FIELD VALIDATIONS || =====================================>
     * =======================
     */

    // id
    protected function validateId(&$id)
    {
        if (empty($id) || !filter_var($id, FILTER_VALIDATE_INT)) {
            $this->error = new \Exception('Insira um valor inteiro válido');
            return false;
        }

        return true;
    }
}