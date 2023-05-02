<template>
    <v-container fluid>
        <h4 class="mb-5">プロジェクト詳細</h4>
        <div class="input-form">
            <v-row>
                <v-col cols="2" style="text-align: right;">
                    プロジェクト名:
                </v-col>
                <v-col cols="4">
                    <v-text-field density="compact" variant="solo" hide-details class="inner-text"
                        v-model="projDetail.PROJ_NAME"></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="2" style="text-align: right;">
                    プロジェクト詳細:
                </v-col>
                <v-col cols="4">
                    <v-textarea density="compact" variant="solo" hide-details class="inner-text"
                        v-model="projDetail.PROJ_DETAIL"></v-textarea>
                </v-col>
            </v-row>
            <v-row v-if='props.projId != ""'>
                <v-col cols="2" style="text-align: right;">
                    ステータス:
                </v-col>
                <v-col cols="3">
                    <v-select density="compact" variant="solo" hide-details class="inner-text" :items="statusList"
                        item-title="STATUS_NAME" item-value="STATUS_CD" v-model="projDetail.STATUS"></v-select>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="2" style="text-align: right;">
                    開始日:
                </v-col>
                <v-col cols="2">
                    <v-text-field density="compact" variant="solo" hide-details class="inner-text"
                        v-model="projDetail.START_DATE"></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="2" style="text-align: right;">
                    完了日:
                </v-col>
                <v-col cols="2">
                    <v-text-field density="compact" variant="solo" hide-details class="inner-text"
                        v-model="projDetail.END_DATE"></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="2" style="text-align: right;">
                    完了予定日:
                </v-col>
                <v-col cols="2">
                    <v-text-field density="compact" variant="solo" hide-details class="inner-text"
                        v-model="projDetail.END_PLAN_DATE"></v-text-field>
                </v-col>
            </v-row>
        </div>
        <div class="mt-5">
            <v-row>
                <v-col cols="5">
                    <v-btn to="/" color="blue-grey-lighten-4">戻る</v-btn>
                </v-col>
                <v-col cols="2">
                    <v-btn v-if='props.projId == ""' @click="createProject" color="blue-accent-2">新規作成</v-btn>
                    <v-btn v-else @click="updateProjDetail" color="blue-accent-2">更新</v-btn>
                </v-col>
            </v-row>
        </div>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
// 前画面（プロジェクトホームからプロジェクトIDを受け取る)
const props = defineProps({
    projId: String
})
// 
// ステータス取得
const statusList = ref([])
onMounted(async () => {
    var res = await axios.get('getProjStatusList')
    statusList.value = res.data
})

// プロジェクトデータ取得
const projDetail = ref({ PROJ_NAME: "", PROJ_DETAIL: "", STATUS: "", START_DATE: "", END_DATE: "", END_PLAN_DATE: "" })
if (props.projId != "") {
    onMounted(async () => {
        var res = await axios.post('getProjDetail', props)
        console.log(JSON.stringify(res))
        if (res.data != undefined) {
            projDetail.value = res.data[0]
        }

    })
}

// プロジェクト詳細更新
const msg = ref([])
const errorMsg = ref([])
const updateProjDetail = async () => {
    // プロジェクト情報にIDを付ける
    projDetail.PROJ_ID = props.projId
    var res = await axios.post('updateProjDetail', projDetail.value);
    // if(res.data.errorMsg != undefined) {
    //     errorMsg.value = res.data.errorMsg
    // } else {
    //     msg.value = res.data.msg
    // }
}

// プロジェクト新規作成
const createProject = async () => {
    var res = await axios.post('createProject', projDetail.value)
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
    max-height: 20px;
    /* border: 1px solid #AAA; */
    /* border-radius: 0px; */
    font-size: 10px !important;
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
