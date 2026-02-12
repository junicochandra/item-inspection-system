<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";

const activeTab = ref(1);
const inspections = ref([]);
const loading = ref(false);

const statusMap = {
    1: 1, // Open
    2: 2, // For Review
    3: 3, // Complete
};

const fetchInspections = async () => {
    loading.value = true;
    try {
        const response = await axios.get("/api/inspections", {
            params: { status_id: statusMap[activeTab.value] },
        });
        inspections.value = response.data.data;
    } finally {
        loading.value = false;
    }
};

const changeTab = (tabNumber) => {
    activeTab.value = tabNumber;
};

onMounted(fetchInspections);

watch(activeTab, fetchInspections);
</script>

<template>
    <div class="container py-4">
        <nav>
            <div class="nav nav-tabs" role="tablist">
                <button
                    class="nav-link"
                    :class="{ active: activeTab === 1 }"
                    @click="changeTab(1)"
                >
                    Open
                </button>
                <button
                    class="nav-link"
                    :class="{ active: activeTab === 2 }"
                    @click="changeTab(2)"
                >
                    For Review
                </button>
                <button
                    class="nav-link"
                    :class="{ active: activeTab === 3 }"
                    @click="changeTab(3)"
                >
                    Complete
                </button>
            </div>
        </nav>

        <div class="tab-content mt-3">
            <div v-if="loading">Loading...</div>
            <div v-else>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Request No</th>
                            <th>Location</th>
                            <th>Scope of Work</th>
                            <th>Type</th>
                            <th>Date Submitted</th>
                            <th>ECD</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="inspection in inspections"
                            :key="inspection.id"
                        >
                            <td>{{ inspection.request_no }}</td>
                            <td>{{ inspection.location?.name }}</td>
                            <td>{{ inspection.scope_of_work?.name }}</td>
                            <td>{{ inspection.service_type?.name }}</td>
                            <td>{{ inspection.submitted_at }}</td>
                            <td>{{ inspection.estimated_completion_date }}</td>
                            <td>{{ inspection.status?.label }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
