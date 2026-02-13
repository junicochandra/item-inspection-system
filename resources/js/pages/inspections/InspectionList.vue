<script setup>
import { onMounted } from "vue";
import { useInspection } from "@/composables/useInspection";
import InspectionTable from "@/components/inspections/InspectionTable.vue";

const { inspections, loading, error, loadInspections } = useInspection();

onMounted(loadInspections);
</script>

<template>
    <div>
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
                            <li class="breadcrumb-item active">
                                Inspection Record
                            </li>
                        </ol>
                    </nav>

                    <div class="d-flex align-items-center gap-3">
                        <h4 class="fw-semibold mb-0">Inspection Record</h4>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="d-flex align-items-center gap-2">
                    <router-link
                        :to="`/inspections/create`"
                        class="btn btn-outline-primary btn-sm"
                    >
                        Create Request
                    </router-link>
                </div>
            </div>
        </div>

        <p v-if="loading">Loading...</p>
        <p v-if="error">Failed to load data</p>

        <InspectionTable v-if="!loading" :data="inspections" />
    </div>
</template>
