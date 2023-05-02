import { createRouter, createWebHistory } from "vue-router";
import home from "../views/Home.vue"
import ProjDetail from "../views/ProjDetail.vue"
import TaskList from "../views/TaskList.vue"
import TaskDetail from "../views/TaskDetail.vue"

const routes = [
    {
        path: "/",
        component: home,
        name: "home"
    },
    {
        path: "/TaskList:projId?",
        component: TaskList,
        name: "TaskList",
        props: true

    },
    {
        // :projIdを追記
        path: "/ProjDetail/:projId?",
        component: ProjDetail,
        name: "ProjDetail",
        props: true

    },
    {
        // :projIdを追記
        path: "/TaskDetail/:projId?/:taskId?",
        component: TaskDetail,
        name: "TaskDetail",
        props: true

    }
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: routes
})

export default router