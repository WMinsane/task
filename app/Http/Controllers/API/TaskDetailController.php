<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskDetailController extends Controller
{   
    // 取得
    public function getTaskDetail(Request $req) {
        $sql = <<<'SQL'
        SELECT
            T.*
            , P.PROJ_NAME 
        FROM
            TASK T 
            LEFT JOIN PROJ P 
                ON T.PROJ_ID = P.PROJ_ID 
        WHERE
            T.PROJ_ID = :projId 
            AND T.TASK_ID = :taskId
        SQL;
        $projId = $req -> input('projId');
        $taskId = $req -> input('taskId');
        $items = DB::select($sql,['projId' => $projId, 'taskId' => $taskId]);
        return $items;
    }
    // ステータス取得
    public function getTaskStatusList() {
        $sql = "SELECT STATUS_CD,STATUS_NAME FROM STATUS WHERE STATUS_KBN = '02'";
        $items = DB::select($sql);
        return $items;
    }

    // プロジェクト更新
    public function updateTaskDetail(Request $req) {
        // パラメータ受け取り
        $params = $req->only(['TASK_NAME', 'TASK_DETAIL', 'PROGRESS', 'STATUS', 'START_DATE', 'END_DATE', 'END_PLAN_DATE','TASK_ID', 'PROJ_ID']);
        $sql = <<<'SQL'
        UPDATE task 
        SET
            TASK_NAME = :TASK_NAME
            , TASK_DETAIL = :TASK_DETAIL
            , PROGRESS = :PROGRESS
            , STATUS = :STATUS
            , START_DATE = :START_DATE
            , END_DATE = :END_DATE
            , END_PLAN_DATE = :END_PLAN_DATE
            , UPDATE_USER = 'test'
            , UPDATE_DATETIME = NOW() 
        WHERE
            TASK_ID = :TASK_ID 
            and PROJ_ID = :PROJ_ID
        SQL;
        $res = DB::update($sql, $params);
        return $res;
    }

    // タスク作成
    public function createTask(Request $req) {
           // パラメータ受け取り
        $params = $req->only(['PROJ_ID', 'TASK_NAME', 'TASK_DETAIL', 'START_DATE', 'END_DATE', 'END_PLAN_DATE']);
        $params['PROJ_ID2'] = $params['PROJ_ID'];
        $sql = <<<'SQL'
        INSERT 
        INTO task( 
            TASK_ID
            , PROJ_ID
            , TASK_NAME
            , TASK_DETAIL
            , PROGRESS
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
                    CAST((SELECT MAX(TASK_ID) FROM TASK X WHERE PROJ_ID = :PROJ_ID) as signed) + 1
                    , 3
                    , '0'
                ) ), '001')
            , :PROJ_ID2
            , :TASK_NAME
            , :TASK_DETAIL
            , '0'
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
