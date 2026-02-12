import { createRouter, createWebHistory } from "vue-router";
import InspectionListPage from "@/pages/inspections/InspectionListPage.vue";
import InspectionDetail from "@/pages/inspections/InspectionDetail.vue";

const routes = [
    {
        path: "/inspections",
        name: "inspections.index",
        component: InspectionListPage,
    },
    {
        path: "/inspections/:id",
        name: "inspections.show",
        component: InspectionDetail,
        props: true,
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
