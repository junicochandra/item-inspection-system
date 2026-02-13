<script setup>
import { ref, onMounted, computed, inject } from "vue";
import { useRoute, useRouter } from "vue-router";
import { formatShortDate } from "@/utils/date";
import { formatUSD } from "@/utils/currency";
import axios from "axios";

const route = useRoute();
const router = useRouter();
const inspection = ref(null);
const loading = ref(true);
const showToast = inject("showToast");

onMounted(() => {
    if (route.query.success === "created") {
        showToast("Inspection created successfully", "success");
        router.replace({ query: {} });
    }

    if (route.query.success === "updated") {
        showToast("Inspection updated successfully", "success");
        router.replace({ query: {} });
    }
});

const badgeClass = (name) => {
    switch (name) {
        case "New":
        case "Good":
        case "Completed":
            return "bg-success";

        case "Minor Defect":
        case "Quarantine":
        case "For Review":
            return "bg-warning text-dark";

        case "Major Defect":
        case "Damaged":
            return "bg-danger";

        case "Rejected":
        case "Scrap":
            return "bg-dark";

        default:
            return "bg-secondary";
    }
};

const approveInspection = async () => {
    try {
        await axios.post(`/api/inspections/${inspection.value.id}/approve`);

        showToast("Orders created successfully", "success");

        await fetchInspection();
    } catch (e) {
        showToast("Failed to approve inspection", "error");
    }
};

const fetchInspection = async () => {
    loading.value = true;

    try {
        const res = await axios.get(`/api/inspections/${route.params.id}`);

        inspection.value = res.data.data;
    } catch (e) {
        console.error(e);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchInspection);

const hasOrderColumn = computed(() => {
    return inspection.value?.inspection_items?.some((item) => item.order);
});

const showModify = computed(() => {
    const blockedStatuses = ["FOR_REVIEW", "COMPLETED"];
    const statusCode = inspection.value?.status?.code;
    return !blockedStatuses.includes(statusCode);
});

const showApprove = computed(() => {
    return inspection.value?.status?.code === "FOR_REVIEW";
});
</script>

<template>
    <div class="container py-4">
        <!-- Loading -->
        <div v-if="loading" class="text-center py-5">
            <div class="spinner-border text-primary"></div>
        </div>

        <div v-else-if="inspection">
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
                            <li class="breadcrumb-item active">Detail</li>
                        </ol>
                    </nav>

                    <div class="d-flex align-items-center gap-3">
                        <h4 class="fw-semibold mb-0">Yard Service Detail</h4>

                        <!-- Status Badge -->
                        <span
                            v-if="inspection?.status"
                            class="badge"
                            :class="badgeClass(inspection.status.name)"
                        >
                            {{ inspection.status.name }}
                        </span>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="d-flex align-items-center gap-2">
                    <router-link
                        :to="`/inspections`"
                        class="btn btn-outline-secondary btn-sm"
                    >
                        ← Back
                    </router-link>

                    <router-link
                        v-if="showModify"
                        :to="`/inspections/${inspection.id}/edit`"
                        class="btn btn-outline-primary btn-sm"
                    >
                        Modify
                    </router-link>

                    <button
                        v-if="showApprove"
                        @click="approveInspection"
                        class="btn btn-success btn-sm"
                    >
                        Approve
                    </button>
                </div>
            </div>

            <!-- SUMMARY CARD -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <small class="text-muted">Request No</small>
                            <div class="fw-semibold">
                                <strong>
                                    {{ inspection.request_no }}
                                </strong>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted">Service Type</small>
                            <div class="fw-semibold">
                                <strong>
                                    {{ inspection.service_type?.name }}
                                </strong>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted">Location</small>
                            <div class="fw-semibold">
                                <strong>{{ inspection.location?.name }}</strong>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted">Status</small>
                            <div>
                                <span
                                    class="badge"
                                    :class="
                                        badgeClass(inspection.status?.label)
                                    "
                                >
                                    {{ inspection.status?.label }}
                                </span>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted">Date Submitted</small>
                            <div class="fw-semibold">
                                <strong>
                                    {{
                                        formatShortDate(inspection.submitted_at)
                                    }}
                                </strong>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted"
                                >Estimated Completion</small
                            >
                            <div class="fw-semibold">
                                <strong
                                    >{{
                                        formatShortDate(
                                            inspection.estimated_completion_date,
                                        )
                                    }}
                                </strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Charge to customer</small>
                            <div class="fw-semibold">
                                <strong>{{ inspection.customer?.name }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SCOPE OF WORK -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">
                    Scope of Work
                </div>

                <div class="card-body">
                    <!-- Service Type Group -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-0 fw-semibold capitalize">
                                <strong>
                                    {{ inspection.scope_of_work?.name }}
                                </strong>
                            </h6>
                        </div>

                        <div
                            v-if="
                                inspection.scope_of_work?.scope_includeds
                                    ?.length
                            "
                            class="table-responsive"
                        >
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 30%">Scope Name</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="scope in inspection.scope_of_work
                                            .scope_includeds"
                                        :key="scope.id"
                                    >
                                        <td>
                                            <span
                                                class="badge bg-light text-dark border"
                                            >
                                                {{ scope.name }}
                                            </span>
                                        </td>
                                        <td class="text-muted">
                                            {{ scope.description }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="text-center text-muted py-4">
                            No scope defined for this inspection.
                        </div>
                    </div>
                </div>
            </div>

            <!-- ITEM INFORMATION -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">
                    Item Information
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered align-middle mb-0"
                            >
                                <thead class="table-light text-center">
                                    <tr>
                                        <th class="text-nowrap">Item No</th>
                                        <th class="text-nowrap">
                                            Item Description
                                        </th>
                                        <th class="text-nowrap">Lot No</th>
                                        <th class="text-nowrap">Allocation</th>
                                        <th class="text-nowrap">Owner</th>
                                        <th class="text-nowrap">Condition</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="item in inspection.inspection_items"
                                        :key="item.id"
                                    >
                                        <td>{{ item.item?.code }}</td>
                                        <td>{{ item.item?.description }}</td>
                                        <td>{{ item.lot?.lot_no }}</td>
                                        <td>{{ item.allocation?.name }}</td>
                                        <td>{{ item.owner?.name }}</td>
                                        <td>
                                            <span
                                                class="badge"
                                                :class="
                                                    badgeClass(
                                                        item.condition?.name,
                                                    )
                                                "
                                            >
                                                {{
                                                    item.condition?.name ??
                                                    "N/A"
                                                }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr
                                        v-if="
                                            !inspection.inspection_items?.length
                                        "
                                    >
                                        <td
                                            colspan="4"
                                            class="text-center text-muted py-3"
                                        >
                                            No items
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARGES -->
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold">
                    Charges to Customer
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th v-if="hasOrderColumn">Order No</th>
                                        <th v-if="hasOrderColumn">
                                            Service Description
                                        </th>
                                        <th class="text-nowrap">Qty</th>
                                        <th class="text-nowrap">Unit Price</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="charge in inspection.inspection_items"
                                        :key="charge.id"
                                    >
                                        <td v-if="hasOrderColumn">
                                            {{ charge.order?.code }}
                                        </td>

                                        <td v-if="hasOrderColumn">
                                            {{ charge.order?.type }}
                                        </td>
                                        <td>{{ charge.qty_required }} PCS</td>
                                        <td>
                                            USD {{ formatUSD(charge.price) }}
                                        </td>
                                        <td class="text-end">
                                            USD {{ formatUSD(charge.subtotal) }}
                                        </td>
                                    </tr>
                                    <tr
                                        v-if="
                                            !inspection.inspection_items?.length
                                        "
                                    >
                                        <td
                                            colspan="5"
                                            class="text-center text-muted py-3"
                                        >
                                            No charges
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <router-link
                    to="/inspections"
                    class="btn btn-outline-secondary btn-sm mt-3 mb-3"
                >
                    ← Back
                </router-link>
            </div>
        </div>
    </div>
</template>
