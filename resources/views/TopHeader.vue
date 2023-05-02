<template>
    <v-app-bar color="blue-lighten-4" density="compact">
        <v-app-bar-nav-icon @click="drawer = !drawer" class="d-flex d-sm-none"></v-app-bar-nav-icon>
        <v-toolbar-title>進捗管理</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon>
            <v-icon>mdi-magnify</v-icon>
        </v-btn>
        <template v-slot:extension>
            <v-tabs v-model="tab" align-with-title class="d-none d-sm-flex">
                <v-tab v-for="item in items" :key="item" :to="item.route">
                    {{ item.text }}
                </v-tab>
            </v-tabs>
        </template>
    </v-app-bar>
    <!-- Add a navigation bar -->
    <v-navigation-drawer v-model="drawer" class="d-flex d-sm-none" absolute temporary>
        <v-list nav dense>
                <v-list-item v-for="(item, index) in items" :to="item.route">
                    <v-list-item-title @click="tab = index">{{ item.text }}</v-list-item-title>
                </v-list-item>
        </v-list>
    </v-navigation-drawer>
    <!-- Navigation bar ends -->
</template>

<script setup>
import { ref } from "vue"
const items = [{text: 'プロジェクト', route: '/'},{text: 'タスク', route: 'TaskList'}]
var drawer = ref(false)
var tab = ref(null)
</script>
