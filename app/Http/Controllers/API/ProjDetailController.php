<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjDetailController extends Controller
{   
    // 取得
    public function getProjDetail(Request $request) {
        $sql = <<<'SQL'
        SELECT
            *
        FROM
            PROJ
        WHERE
            PROJ_ID = :projId

        SQL;
        $projId = $request -> input('projId');
        Log::alert($sql);
        $items = DB::select($sql,['projId' => $projId]);
        return $items;
    }
    // ステータス取得
    public function getProjStatusList() {
        $sql = "SELECT STATUS_CD,STATUS_NAME FROM STATUS WHERE STATUS_KBN = '01'";
        $items = DB::select($sql);
        return $items;
    }

    // プロジェクト更新
    public function updateProjDetail(Request $req) {
        // パラメータ受け取り
        $params = $req->only(['PROJ_NAME', 'PROJ_DETAIL', 'STATUS', 'START_DATE', 'END_DATE', 'END_PLAN_DATE', 'PROJ_ID']);
        $sql = <<<'SQL'
        UPDATE proj 
        SET
            PROJ_NAME = :PROJ_NAME
            , PROJ_DETAIL = :PROJ_DETAIL
            , STATUS = :STATUS
            , START_DATE = :START_DATE
            , END_DATE = :END_DATE
            , END_PLAN_DATE = :END_PLAN_DATE
            , UPDATE_USER = 'test'
            , UPDATE_DATETIME = NOW() 
        WHERE
            PROJ_ID = :PROJ_ID
        SQL;
        $res = DB::update($sql, $params);
        return $res;
    }

    // プロジェクト作成
    public function createProject(Request $req) {
           // パラメータ受け取り
        $params = $req->only(['PROJ_NAME', 'PROJ_DETAIL', 'START_DATE', 'END_DATE', 'END_PLAN_DATE']);
        $sql = <<<'SQL'
        INSERT 
        INTO proj( 
            PROJ_ID
            , PROJ_NAME
            , PROJ_DETAIL
            , STATUS
            , START_DATE
            , END_DATE
            , END_PLAN_DATE
            , DELETE_FLG
            , CREATE_USER
            , CREATE_DATETIME
            , UPDATE_USER
            , UPDATE_DATETIME
        ) 
        VALUES ( 
            COALESCE((SELECT
                LPAD( 
                    CAST((SELECT MAX(PROJ_ID) FROM PROJ X) as signed) + 1
                    , 3
                    , '0'
                ) ), '001')
                , :PROJ_NAME
                , :PROJ_DETAIL
                , '1'
                , :START_DATE
                , :END_DATE
                , :END_PLAN_DATE
                , '0'
                , 'test'
                , NOW()
                , 'test'
                , NOW()
        )
        SQL;         
        $res = DB::insert($sql,$params);
        return $res;
    }
}
