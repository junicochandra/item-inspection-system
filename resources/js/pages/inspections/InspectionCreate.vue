<script setup>
import { onMounted } from "vue";
import { useInspection } from "@/composables/useInspection";
import InspectionForm from "../../components/inspections/InspectionForm.vue";

const { inspections, loading, error, loadInspections } = useInspection();

onMounted(loadInspections);
</script>

<template>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav style="--bs-breadcrumb-divider: &quot;>&quot;">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item">
                            <router-link to="/inspections"
                                >Inspections</router-link
                            >
                        </li>
                        <li class="breadcrumb-item active">
                            Inspection Create
                        </li>
                    </ol>
                </nav>
                <h4 class="fw-semibold mb-0">Inspection Create</h4>
            </div>
        </div>
    </div>

    <InspectionForm />
</template>
