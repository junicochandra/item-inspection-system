import { createRouter, createWebHistory } from "vue-router";
import InspectionList from "@/pages/inspections/InspectionList.vue";
import InspectionDetail from "@/pages/inspections/InspectionDetail.vue";
import InspectionCreate from "@/pages/inspections/InspectionCreate.vue";

const routes = [
    {
        path: "/inspections",
        name: "inspections.index",
        component: InspectionList,
    },
    {
        path: "/inspections/create",
        name: "inspections.create",
        component: InspectionCreate,
    },
    {
        path: "/inspections/:id",
        name: "inspections.show",
        component: InspectionDetail,
        props: true,
    },
    {
        path: "/inspections/:id/edit",
        name: "inspections.edit",
        component: () => import("@/pages/inspections/InspectionEdit.vue"),
        props: true,
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
