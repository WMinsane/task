<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskListController extends Controller
{
    public function getTaskList(Request $req) {
        $params = $req->only(['PROJ_ID','START_DATE_FROM','START_DATE_TO','CONTAIN_COMPLETE_FLG']);
        $params['PROJ_ID2'] = $params['PROJ_ID'];
        $params['PROJ_ID3'] = $params['PROJ_ID'];
        $params['START_DATE_FROM2'] = $params['START_DATE_FROM'];
        $params['START_DATE_FROM3'] = $params['START_DATE_FROM'];
        $params['START_DATE_TO2'] = $params['START_DATE_TO'];
        $params['START_DATE_TO3'] = $params['START_DATE_TO'];
        $params['CONTAIN_COMPLETE_FLG2'] = $params['CONTAIN_COMPLETE_FLG'];
        $params['CONTAIN_COMPLETE_FLG3'] = $params['CONTAIN_COMPLETE_FLG'];
        $sql = <<<'SQL'
        SELECT
            P.PROJ_ID
            , P.PROJ_NAME
            , T.TASK_ID
            , T.TASK_NAME
            , T.TASK_DETAIL
            , T.PROGRESS
            , S.STATUS_NAME
            , DATE_FORMAT(T.START_DATE, '%Y/%m/%d') START_DATE 
            , DATE_FORMAT(T.END_DATE, '%Y/%m/%d') END_DATE 
            , DATE_FORMAT(T.END_PLAN_DATE, '%Y/%m/%d') END_PLAN_DATE 
        FROM
            TASK T 
            LEFT JOIN PROJ P 
                ON T.PROJ_ID = P.PROJ_ID 
            LEFT JOIN STATUS S 
                ON S.STATUS_KBN = '02' 
                AND T.STATUS = S.STATUS_CD 
        WHERE
            T.DELETE_FLG <> '1' 
            AND ( 
                :PROJ_ID IS NULL 
                OR :PROJ_ID2 = '' 
                OR P.PROJ_ID = :PROJ_ID3
            ) 
            AND ( 
                :START_DATE_FROM IS NULL 
                OR :START_DATE_FROM2 = '' 
                OR T.START_DATE >= :START_DATE_FROM3
            ) 
            AND ( 
                :START_DATE_TO IS NULL 
                OR :START_DATE_TO2 = '' 
                OR T.START_DATE <= :START_DATE_TO3
            ) 
            AND ( 
                :CONTAIN_COMPLETE_FLG IS NULL 
                OR :CONTAIN_COMPLETE_FLG2 = '' 
                OR :CONTAIN_COMPLETE_FLG3 = '1' 
                OR T.STATUS <> '2'
            )
        SQL;
        
        $items = DB::select($sql,$params);
        return $items;
    }

    public function getProjItems() {
        $sql = 'SELECT PROJ_ID, PROJ_NAME FROM PROJ';
        $res = DB::select($sql);
        return $res;
    }
}
