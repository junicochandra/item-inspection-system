<script setup>
import { onMounted } from "vue";
import { useRoute } from "vue-router";
import { useInspection } from "@/composables/useInspection";
import InspectionForm from "../../components/inspections/InspectionForm.vue";

const route = useRoute();
const { loadInspections } = useInspection();

onMounted(loadInspections);

const inspectionId = route.params.id;
</script>

<template>
    <div class="container py-4">
        <!-- HEADER -->
        <div
            class="d-flex justify-content-between align-items-start mb-4 pb-3 border-bottom"
        >
            <!-- LEFT -->
            <div>
                <nav style="--bs-breadcrumb-divider: &quot;>&quot;">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item">
                            <router-link to="/inspections"
                                >Inspections</router-link
                            >
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>

                <div class="d-flex align-items-center gap-3">
                    <h4 class="fw-semibold mb-0">Inspection Edit</h4>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="d-flex align-items-center gap-2">
                <router-link
                    :to="`/inspections/${inspectionId}`"
                    class="btn btn-outline-secondary btn-sm"
                >
                    ← Back
                </router-link>
            </div>
        </div>

        <InspectionForm />
    </div>
</template>
