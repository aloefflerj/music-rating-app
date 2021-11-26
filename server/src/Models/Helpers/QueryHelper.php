<?php

namespace MusicRating\Models\Helpers;

trait QueryHelper
{
   
    public function select($columns, $table, $params = null) 
    {
        $params = $params ? "AND {$params}" : null;
        return "SELECT {$columns} FROM {$table} WHERE 1 = 1 $params";
    }
    
}