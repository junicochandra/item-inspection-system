<script setup>
import { reactive, ref, watch, onMounted } from "vue";
import axios from "axios";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import { useInspectionReferences } from "@/composables/useInspectionReferences";

/* =====================================================
 * FORM STATE
 * ===================================================== */

const form = reactive({
    request_no: "",
    location_id: null,
    service_type_id: null,
    scope_of_work_id: null,
    customer_id: null,
    status_id: null,
    submitted_at: "",
    estimated_completion_date: "",
    charge_to_customer: false,
    note: "",
});

const scopeIncludeds = ref([]);

/* =====================================================
 * REFERENCES
 * ===================================================== */

const { refs, load } = useInspectionReferences();

/* =====================================================
 * INFORMATION FILTER STATE
 * ===================================================== */

const selected = reactive({
    lot: null,
    allocation: null,
    owner: null,
    condition: null,
});

const options = reactive({
    lots: [],
    allocations: [],
    owners: [],
    conditions: [],
});

/* =====================================================
 * API CALLS
 * ===================================================== */

const fetchScopeIncluded = async (scopeId) => {
    if (!scopeId) {
        scopeIncludeds.value = [];
        return;
    }

    try {
        const { data } = await axios.get(`/api/scope-included/${scopeId}`);
        scopeIncludeds.value = data.success ? data.data : [];
    } catch (error) {
        console.error(error);
        scopeIncludeds.value = [];
    }
};

const fetchFilter = async () => {
    const { data } = await axios.get("/api/lot-filter", {
        params: {
            lot_id: selected.lot?.id ?? null,
            allocation_id: selected.allocation?.id ?? null,
            owner_id: selected.owner?.id ?? null,
            condition_id: selected.condition?.id ?? null,
        },
    });

    options.lots = data.lots;
    options.allocations = data.allocations;
    options.owners = data.owners;
    options.conditions = data.conditions;
};

const submit = async () => {
    try {
        await axios.post("/api/inspections", form);
        alert("Inspection created");
    } catch (error) {
        console.error(error);
        alert("Failed to create inspection");
    }
};

/* =====================================================
 * LIFECYCLE
 * ===================================================== */

onMounted(async () => {
    await load();

    // Ensure arrays are always defined
    refs.locations = refs.locations || [];
    refs.serviceTypes = refs.serviceTypes || [];
    refs.scopeOfWorks = refs.scopeOfWorks || [];
    refs.customers = refs.customers || [];
    refs.statuses = refs.statuses || [];

    await fetchFilter();
});

/* =====================================================
 * WATCHERS
 * ===================================================== */

// Scope Included
watch(
    () => form.scope_of_work_id,
    async (newId) => {
        await fetchScopeIncluded(newId);
    },
);

// Filter watchers (controlled cascade reset)

watch(
    () => selected.lot,
    async () => {
        selected.allocation = null;
        selected.owner = null;
        selected.condition = null;
        await fetchFilter();
    },
);

watch(
    () => selected.allocation,
    async () => {
        selected.owner = null;
        selected.condition = null;
        await fetchFilter();
    },
);

watch(
    () => selected.owner,
    async () => {
        selected.condition = null;
        await fetchFilter();
    },
);

/* =====================================================
 * HELPERS
 * ===================================================== */

const mapToId = (value) => value?.id || null;
</script>

<template>
    <div class="container py-4">
        <form @submit.prevent="submit" class="row g-3">
            <div class="col-md-12">
                <h3>Create Inspection</h3>
            </div>

            <!-- ===================== -->
            <!-- Inspection Information -->
            <!-- ===================== -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-semibold">Inspection Information</h5>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <!-- Service Type -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Service Type <span class="text-danger">*</span>
                            </label>
                            <Multiselect
                                :options="refs.serviceTypes || []"
                                label="name"
                                track-by="id"
                                placeholder="Select Service Type"
                                :searchable="true"
                                :close-on-select="true"
                                :model-value="
                                    (refs.serviceTypes || []).find(
                                        (s) => s.id === form.service_type_id,
                                    ) || null
                                "
                                @update:model-value="
                                    (val) =>
                                        (form.service_type_id = mapToId(val))
                                "
                            />
                        </div>

                        <!-- Scope of Work -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Scope of Work <span class="text-danger">*</span>
                            </label>
                            <Multiselect
                                :options="refs.scopeOfWorks || []"
                                label="name"
                                track-by="id"
                                placeholder="Select a Scope of Work Name"
                                :searchable="true"
                                :close-on-select="true"
                                :model-value="
                                    (refs.scopeOfWorks || []).find(
                                        (s) => s.id === form.scope_of_work_id,
                                    ) || null
                                "
                                @update:model-value="
                                    (val) =>
                                        (form.scope_of_work_id =
                                            val?.id || null)
                                "
                            />
                        </div>

                        <!-- Scope Included -->
                        <div class="col-md-12">
                            <label class="form-label">Scope Included</label>
                            <div class="border rounded p-2 bg-light min-vh-25">
                                <div class="d-flex flex-wrap gap-2">
                                    <span
                                        v-for="included in scopeIncludeds"
                                        :key="included.id"
                                        class="badge bg-secondary"
                                    >
                                        {{ included.name }}
                                    </span>
                                    <span
                                        v-if="!scopeIncludeds.length"
                                        class="text-muted"
                                    >
                                        No scope included
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="col-lg-6">
                            <label class="form-label">
                                Location <span class="text-danger">*</span>
                            </label>
                            <Multiselect
                                :options="refs.locations || []"
                                label="name"
                                track-by="id"
                                placeholder="Select Location"
                                :searchable="true"
                                :close-on-select="true"
                                :model-value="
                                    (refs.locations || []).find(
                                        (l) => l.id === form.location_id,
                                    ) || null
                                "
                                @update:model-value="
                                    (val) => (form.location_id = mapToId(val))
                                "
                            />
                        </div>

                        <!-- Estimated Completion -->
                        <div class="col-lg-6">
                            <label class="form-label">
                                Estimated Completion Date
                                <span class="text-danger">*</span>
                            </label>
                            <input
                                type="date"
                                v-model="form.estimated_completion_date"
                                class="form-control"
                            />
                        </div>

                        <!-- Customer -->
                        <div class="col-lg-6">
                            <label class="form-label">
                                Customer <span class="text-danger">*</span>
                            </label>
                            <Multiselect
                                :options="refs.customers || []"
                                label="name"
                                track-by="id"
                                placeholder="Select Customer Name"
                                :searchable="true"
                                :close-on-select="true"
                                :model-value="
                                    (refs.customers || []).find(
                                        (c) => c.id === form.customer_id,
                                    ) || null
                                "
                                @update:model-value="
                                    (val) => (form.customer_id = mapToId(val))
                                "
                            />
                        </div>

                        <!-- Status -->
                        <div class="col-lg-6">
                            <label class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <Multiselect
                                :options="refs.statuses || []"
                                label="label"
                                track-by="id"
                                placeholder="Select Status"
                                :searchable="true"
                                :close-on-select="true"
                                :model-value="
                                    (refs.statuses || []).find(
                                        (s) => s.id === form.status_id,
                                    ) || null
                                "
                                @update:model-value="
                                    (val) => (form.status_id = mapToId(val))
                                "
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== -->
            <!-- Order Information -->
            <!-- ===================== -->
            <div class="card shadow-sm mb-4">
                <div
                    class="card-header bg-white d-flex justify-content-between align-items-center"
                >
                    <h5 class="mb-0 fw-semibold">Order Information</h5>
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-primary"
                    >
                        + Add Item
                    </button>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <!-- Lot Selection -->
                        <div class="col-lg-3">
                            <label class="form-label">
                                Lot Selection <span class="text-danger">*</span>
                            </label>
                            <Multiselect
                                v-model="selected.lot"
                                :options="options.lots"
                                label="lot_no"
                                track-by="id"
                                placeholder="Select Lot"
                            />
                        </div>

                        <!-- Allocation -->
                        <div class="col-lg-3">
                            <label class="form-label">
                                Allocation <span class="text-danger">*</span>
                            </label>
                            <Multiselect
                                v-model="selected.allocation"
                                :options="options.allocations"
                                label="name"
                                track-by="id"
                                placeholder="Select Allocation"
                                :disabled="!options.allocations.length"
                            />
                        </div>

                        <!-- Owner -->
                        <div class="col-lg-3">
                            <label class="form-label">
                                Owner <span class="text-danger">*</span>
                            </label>
                            <Multiselect
                                v-model="selected.owner"
                                :options="options.owners"
                                label="name"
                                track-by="id"
                                placeholder="Select Owner"
                                :disabled="!options.owners.length"
                            />
                        </div>

                        <!-- Condition -->
                        <div class="col-lg-3">
                            <label class="form-label">
                                Condition <span class="text-danger">*</span>
                            </label>
                            <Multiselect
                                v-model="selected.condition"
                                :options="options.conditions"
                                label="name"
                                track-by="id"
                                placeholder="Select Condition"
                                :disabled="!options.conditions.length"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===================== -->
            <!-- Note to Yard -->
            <!-- ===================== -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-semibold">Note to Yard</h5>
                </div>
                <div class="card-body">
                    <textarea
                        v-model="form.note"
                        class="form-control"
                        placeholder="Enter Note"
                        rows="5"
                    ></textarea>
                </div>
            </div>

            <!-- Submit -->
            <div class="text-end">
                <button class="btn btn-primary px-4">Submit Inspection</button>
            </div>
        </form>
    </div>
</template>

<style>
.badge {
    font-size: 0.9rem;
    padding: 0.5em 0.75em;
}
</style>
