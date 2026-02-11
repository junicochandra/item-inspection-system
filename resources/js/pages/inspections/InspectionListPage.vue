<script setup>
import { onMounted } from "vue";
import { useInspection } from "@/composables/useInspection";
import InspectionTable from "@/components/inspections/InspectionTable.vue";
import InspectionForm from "../../components/inspections/InspectionForm.vue";

const { inspections, loading, error, loadInspections } = useInspection();

onMounted(loadInspections);
</script>

<template>
    <div>
        <h1>Inspections</h1>

        <p v-if="loading">Loading...</p>
        <p v-if="error">Failed to load data</p>

        <InspectionTable v-if="!loading" :data="inspections" />

        <InspectionForm />
    </div>
</template>
