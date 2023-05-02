<template>
    <v-container fluid>
        <h4 class="mb-5">プロジェクト一覧</h4>
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
                            ステータス
                        </th>
                        <th>
                            開始日
                        </th>
                        <th>
                            完了日
                        </th>
                        <th>
                            完了予定日
                        </th>
                        <th>
                            
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in projList" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td><a href="#" @click="moveDetail(item.PROJ_ID)">{{ item.PROJ_NAME }}</a></td>
                        <td>{{ item.STATUS_NAME }}</td>
                        <td>{{ item.START_DATE }}</td>
                        <td>{{ item.END_DATE }}</td>
                        <td>{{ item.END_PLAN_DATE }}</td>
                        <td>
                            <v-btn @click="moveTaskList(item.PROJ_ID)" density="compact" class="mx-4" color="green">タスク確認</v-btn>
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
                    <v-btn @click="createProject" color="blue-accent-2">新規作成</v-btn>
                </v-col>
            </v-row>
        </div>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import {useRouter} from 'vue-router'

// プロジェクト一覧取得
const projList = ref([])
onMounted(async () => {
    var res = await axios.get('getProjList')
    projList.value = res.data
})

// プロジェクト詳細に遷移(更新モード)
const router = useRouter()
const moveDetail = (projId) => {
    router.push({name: 'ProjDetail', params:{ projId: projId}})
}

// プロジェクト詳細に遷移(新規モード)
const createProject = () => {
    router.push({name: 'ProjDetail', params:{ projId: null}})
}

// タスク一覧に遷移
const moveTaskList = (projId) => {
    router.push({name: 'TaskList', params:{projId: projId}})
}
</script>
