import { createRouter, createWebHistory } from "vue-router";
import InspectionListPage from "@/pages/inspections/InspectionListPage.vue";

const routes = [
    {
        path: "/inspections",
        name: "inspections.index",
        component: InspectionListPage,
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
