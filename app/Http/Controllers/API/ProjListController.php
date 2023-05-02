<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProjListController extends Controller
{
    public function getProjList() {
        $sql = <<<'SQL'
        SELECT
            P.*
            , S.STATUS_NAME 
        FROM
            PROJ P 
            LEFT JOIN STATUS S 
                ON S.STATUS_KBN = '01' 
                AND P.STATUS = S.STATUS_CD 
        WHERE
            P.DELETE_FLG <> '1';        
        SQL;
        
        $items = DB::select($sql);
        return $items;
    }
}
