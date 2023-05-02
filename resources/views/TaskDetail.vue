<template>
    <v-container fluid>
        <h4 class="mb-5">タスク詳細</h4>
        <div class="input-form">
            <v-row>
                <v-col cols="2" style="text-align: right;">
                    プロジェクト:
                </v-col>
                <v-col cols="4">
                    <v-select v-if="props.taskId == undefined" density="compact" variant="solo" hide-details class="inner-text" :items="projItems"
                        item-title="PROJ_NAME" item-value="PROJ_ID" v-model="taskDetail.PROJ_ID"></v-select>
                    <span v-else>{{ taskDetail.PROJ_NAME }}</span>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="2" style="text-align: right;">
                    タスク名:
                </v-col>
                <v-col cols="4">
                    <v-text-field density="compact" variant="solo" hide-details class="inner-text"
                        v-model="taskDetail.TASK_NAME"></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="2" style="text-align: right;">
                    タスク詳細:
                </v-col>
                <v-col cols="4">
                    <v-textarea density="compact" variant="solo" hide-details class="inner-text"
                        v-model="taskDetail.TASK_DETAIL"></v-textarea>
                </v-col>
            </v-row>
            <v-row v-if="props.taskId != undefined">
                <v-col cols="2" style="text-align: right;">
                    ステータス:
                </v-col>
                <v-col cols="3">
                    <v-select density="compact" variant="solo" hide-details class="inner-text" :items="statusList"
                        item-title="STATUS_NAME" item-value="STATUS_CD" v-model="taskDetail.STATUS"></v-select>
                </v-col>
            </v-row>
            <v-row v-if="props.taskId != undefined">
                <v-col cols="2" style="text-align: right;">
                    進捗率（%）:
                </v-col>
                <v-col cols="2">
                    <v-text-field density="compact" variant="solo" hide-details class="inner-text"
                        v-model="taskDetail.PROGRESS"></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="2" style="text-align: right;">
                    開始日:
                </v-col>
                <v-col cols="2">
                    <v-text-field density="compact" variant="solo" hide-details class="inner-text"
                        v-model="taskDetail.START_DATE"></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="2" style="text-align: right;">
                    完了日:
                </v-col>
                <v-col cols="2">
                    <v-text-field density="compact" variant="solo" hide-details class="inner-text"
                        v-model="taskDetail.END_DATE"></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="2" style="text-align: right;">
                    完了予定日:
                </v-col>
                <v-col cols="2">
                    <v-text-field density="compact" variant="solo" hide-details class="inner-text"
                        v-model="taskDetail.END_PLAN_DATE"></v-text-field>
                </v-col>
            </v-row>
        </div>
        <div class="mt-5">
            <v-row>
                <v-col cols="5">
                    <v-btn to="/TaskList" color="blue-grey-lighten-4">戻る</v-btn>
                </v-col>
                <v-col cols="2">
                    <v-btn v-if="props.taskId == undefined" @click="createTask" color="blue-accent-2">新規作成</v-btn>
                    <v-btn v-else @click="updateTaskDetail" color="blue-accent-2">更新</v-btn>
                </v-col>
            </v-row>
        </div>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
// 前画面（プロジェクトホームからプロジェクトIDを受け取る)
const props = defineProps({
    projId: String,
    taskId: String
})
// 初期表示画面処理
const projItems = ref([])
const statusList = ref([])
onMounted(async () => {
    // プロジェクトリスト作成
    var res = await axios.get('getProjItems')
    projItems.value = res.data
    // ステータス取得
    var res = await axios.get('getProjStatusList')
    statusList.value = res.data

})

// プロジェクトデータ取得
const taskDetail = ref({ PROJ_ID: "", TASK_ID: "", TASK_NAME: "", TASK_DETAIL: "", START_DATE: "",
 END_DATE: "", END_PLAN_DATE: "" ,STATUS: "", PROGRESS: "",})
if (props.taskId != undefined) {
    onMounted(async () => {
        var res = await axios.post('getTaskDetail', props)
        if (res.data != undefined) {
            taskDetail.value = res.data[0]
        }

    })
}

// タスク更新
const msg = ref([])
const errorMsg = ref([])
const updateTaskDetail = async () => {
    // プロジェクト情報にIDを付ける
    taskDetail.PROJ_ID = props.projId
    taskDetail.TASK_ID = props.taskId
    var res = await axios.post('updateTaskDetail', taskDetail.value);
    // if(res.data.errorMsg != undefined) {
    //     errorMsg.value = res.data.errorMsg
    // } else {
    //     msg.value = res.data.msg
    // }
}

// プロジェクト新規作成
const createTask = async () => {
    var res = await axios.post('createTask', taskDetail.value)
    console.log(JSON.stringify(res))
    // if(res.data.errorMsg != undefined) {
    //     errorMsg.value = res.data.errorMsg
    // } else {
    //     msg.value = res.data.msg
    // }
}
</script>
<style>
.input-form {
    font-size: 14px;
    min-width: 600px;
}

.inner-text input,
textarea {
    padding: 0px 2px 0px 2px;
    min-height: 20px;
    /* border: 1px solid #AAA; */
    /* border-radius: 0px; */
    font-size: 10px;
}

/* 以下プルダウンの設定 */
.v-select .v-field__field {
    max-height: 24px;
    margin-bottom: 0px;
}

.v-select .v-field__input {
    padding-top: 4px;
    padding-left: 4px;
    font-size: 12px;
}

.v-select .v-field__append-inner {
    padding: 0px;
}

.v-select .v-select__selection-text {
    font-size: 12px;
}

.v-text-field input {
    font-size: 1.2em;
}

.v-label {
    font-size: 10px !important;
}

.v-overlay-container {
    font-size: 10px !important;
}

.v-label {
    font-size: 10px !important;
}
</style>
