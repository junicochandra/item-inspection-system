<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { formatShortDate } from "@/utils/date";
import axios from "axios";

const route = useRoute();
const inspection = ref(null);
const loading = ref(true);

onMounted(async () => {
    try {
        const res = await axios.get(`/api/inspections/${route.params.id}`);
        inspection.value = res.data.data;
    } catch (e) {
        console.error(e);
    } finally {
        loading.value = false;
    }
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
            <div class="d-flex justify-content-between align-items-center mb-4">
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
                    <h4 class="fw-semibold mb-0">Yard Service Detail</h4>
                </div>

                <div>
                    <router-link
                        :to="`/inspections/${inspection.id}/edit`"
                        class="btn btn-outline-primary btn-sm"
                    >
                        Modify
                    </router-link>
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
                                <span class="badge bg-success">
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
                <div class="card-header bg-light fw-semibold">
                    Item Information
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Lot No</th>
                                <th>Allocation</th>
                                <th>Owner</th>
                                <th>Condition</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in inspection.items" :key="item.id">
                                <td>{{ item.lot?.lot_no }}</td>
                                <td>{{ item.allocation?.name }}</td>
                                <td>{{ item.owner?.name }}</td>
                                <td>
                                    <span
                                        class="badge"
                                        :class="{
                                            'bg-success':
                                                item.condition?.name === 'Good',
                                            'bg-warning text-dark':
                                                item.condition?.name ===
                                                'Quarantine',
                                            'bg-secondary': !item.condition,
                                        }"
                                    >
                                        {{ item.condition?.name }}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="!inspection.items?.length">
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

            <!-- CHARGES -->
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-semibold">
                    Charges to Customer
                </div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Service</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="charge in inspection.charges"
                                :key="charge.id"
                            >
                                <td>{{ charge.service_name }}</td>
                                <td>{{ charge.qty }}</td>
                                <td>{{ charge.unit_price }}</td>
                                <td class="text-end">
                                    {{ charge.total }}
                                </td>
                            </tr>
                            <tr v-if="!inspection.charges?.length">
                                <td
                                    colspan="4"
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
</template>
