<template>
    <v-container fluid>
        <h4 class="mb-5">タスク一覧検索画面</h4>
        <div class="search-form">
            <v-row>
                <v-col cols="2" style="text-align: right;">
                    プロジェクト:
                </v-col>
                <v-col cols="4">
                    <v-select density="compact" variant="solo" hide-details class="inner-text" :items="projItems"
                        item-title="PROJ_NAME" item-value="PROJ_ID" v-model="searchForm.PROJ_ID"></v-select>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="2" style="text-align: right;">
                    起票日:
                </v-col>
                <v-col cols="2">
                    <v-text-field v-model="searchForm.START_DATE_FROM" density="compact" variant="solo" hide-details
                        class="inner-text"></v-text-field>
                </v-col>
                <v-col cols="1">
                    ～
                </v-col>
                <v-col cols="2">
                    <v-text-field v-model="searchForm.START_DATE_TO" density="compact" variant="solo" hide-details
                        class="inner-text"></v-text-field>
                </v-col>
                <v-col cols="4"></v-col>
            </v-row>
            <v-row>
                <v-col cols="2" style="text-align: right;">
                    完了済みを含む:
                </v-col>
                <v-col cols="6" class="pl-4">
                    <input type="checkbox" style="transform:scale(2.0);" v-model="searchForm.CONTAIN_COMPLETE_FLG" />
                </v-col>
                <v-col cols="1">
                    <v-btn @click="getTaskList" color="blue-accent-2">検索</v-btn>
                </v-col>
            </v-row>
        </div>
        <div class="table-list">
            <v-table density="compact">
                <thead>
                    <tr>
                        <th>
                            No.
                        </th>
                        <th>
                            プロジェクト名
                        </th>
                        <th>
                            タスク名
                        </th>
                        <th>
                            タスク詳細
                        </th>
                        <th>
                            進捗率
                        </th>
                        <th>
                            ステータス
                        </th>
                        <th>
                            起票日
                        </th>
                        <th>
                            編集・削除
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in taskList" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ item.PROJ_NAME }}</td>
                        <td>{{ item.TASK_NAME }}</td>
                        <td>{{ item.TASK_DETAIL }}</td>
                        <td>{{ item.PROGRESS + "%" }}</td>
                        <td>{{ item.STATUS_NAME }}</td>
                        <td>{{ item.START_DATE }}</td>
                        <td>
                            <v-btn @click="updateTask(item.PROJ_ID,item.TASK_ID)" density="compact" class="mx-4" color="green">更新</v-btn>
                            <v-btn density="compact" color="red">削除</v-btn>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </div>
        <div class="mt-5">
            <v-row>
                <v-col cols = "10"></v-col>
                <v-col cols = "1">
                    <v-btn @click="createTask" color="blue-accent-2">新規作成</v-btn>
                </v-col>
            </v-row>
        </div>

    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

// 初期化
const searchForm = ref({ PROJ_ID: "", START_DATE_FROM: "", START_DATE_TO: "", CONTAIN_COMPLETE_FLG: true })

// 画面遷移パラメータの受け取り
const props =
    defineProps({
        projId: String
    })

// 初期表示時処理
const projItems = ref([])
onMounted(async () => {
    // プルダウン作成
    var res = await axios.get('getProjItems')
    projItems.value = res.data

    // 前画面からのプロジェクト引継ぎ
    console.log(props.projId)
    if (props.projId != "") {
        searchForm.value.PROJ_ID = props.projId
        // 検索を行う
        getTaskList()
    }
})

// 検索
const taskList = ref([])
const getTaskList = async () => {
    // 参照ではなく値をコピー
    var searchFormCopy = Object.assign({},searchForm.value) 
    var compFlg = searchFormCopy.CONTAIN_COMPLETE_FLG
    if (compFlg != undefined && compFlg === true) {
        searchFormCopy.CONTAIN_COMPLETE_FLG = '1'
    } else {
        searchFormCopy.CONTAIN_COMPLETE_FLG = '0'
    }
    console.log(JSON.stringify(searchFormCopy))
    var res = await axios.post('getTaskList', searchFormCopy)
    console.log(JSON.stringify(res))
    taskList.value = res.data
}

// タスク新規作成
const router = useRouter()
const createTask = () => {
    router.push({name: 'TaskDetail'})
}

// タスク更新
const updateTask = (projId, taskId) => {
    console.log('タスクパラメータ' + projId + ' ' + taskId)
    router.push({name: 'TaskDetail', params: {projId: projId, taskId: taskId}})
} 

</script>
<style>
.search-form {
    font-size: 14px;
    min-width: 600px;
}

.inner-text input {
    padding: 0px 2px 0px 2px;
    min-height: 20px;
    border: 1px solid #AAA;
    border-radius: 0px;
    font-size: 12px;
    font-size: 10px;
}

.table-list {
    font-size: 12px;
    padding-top: 30px;
}
</style>