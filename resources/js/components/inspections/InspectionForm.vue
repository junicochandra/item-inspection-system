<script setup>
import { reactive, ref, watch, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import { useInspectionReferences } from "@/composables/useInspectionReferences";

/* =====================================================
 * FORM STATE
 * ===================================================== */

const form = reactive({
    location_id: null,
    service_type_id: null,
    scope_of_work_id: null,
    customer_id: null,
    status_id: null,
    submitted_at: "",
    estimated_completion_date: "",
    charge_to_customer: false,
    note: "",
    items: [],
});

const scopeIncludeds = ref([]);

/* =====================================================
 * REFERENCES
 * ===================================================== */

const { refs, load } = useInspectionReferences();

/* =====================================================
 * MULTI ROW STATE
 * ===================================================== */

const createEmptyItem = () => ({
    lot: null,
    allocation: null,
    owner: null,
    condition: null,
    qtyRequired: null,
    options: {
        lots: [],
        allocations: [],
        owners: [],
        conditions: [],
    },
});

/* =====================================================
 * ITEMS ARRAY
 * ===================================================== */

const addItem = async () => {
    const newItem = createEmptyItem();
    form.items.push(newItem);
    await fetchFilter(form.items.length - 1);
};

const removeItem = (index) => {
    form.items.splice(index, 1);
};

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

/* =====================================================
 * FETCH PER ROW
 * ===================================================== */

const fetchFilter = async (index) => {
    const item = form.items[index];

    const { data } = await axios.get("/api/lot-filter", {
        params: {
            lot_id: item.lot?.id ?? null,
            allocation_id: item.allocation?.id ?? null,
            owner_id: item.owner?.id ?? null,
            condition_id: item.condition?.id ?? null,
        },
    });

    item.options.lots = data.lots;
    item.options.allocations = data.allocations;
    item.options.owners = data.owners;
    item.options.conditions = data.conditions;
};

/* =====================================================
 * CASCADE RESET PER ROW
 * ===================================================== */

const onLotChange = async (index) => {
    const item = form.items[index];
    item.allocation = null;
    item.owner = null;
    item.condition = null;
    item.qtyRequired = null;
    await fetchFilter(index);
};

const onAllocationChange = async (index) => {
    const item = form.items[index];
    item.owner = null;
    item.condition = null;
    await fetchFilter(index);
};

const onOwnerChange = async (index) => {
    const item = form.items[index];
    item.condition = null;
    await fetchFilter(index);
};

/* =====================================================
 * SUBMIT
 * ===================================================== */

const router = useRouter();
const submit = async () => {
    try {
        const payload = {
            ...form,
            items: form.items.map((item) => ({
                lot_id: item.lot?.id ?? null,
                allocation_id: item.allocation?.id ?? null,
                owner_id: item.owner?.id ?? null,
                condition_id: item.condition?.id ?? null,
                qty_required: item.qtyRequired ?? 0,
            })),
        };

        const response = await axios.post("/api/inspections", payload);
        const inspectionId = response.data.data.id;
        router.push(`/inspections/${inspectionId}`);
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

    refs.locations = refs.locations || [];
    refs.serviceTypes = refs.serviceTypes || [];
    refs.scopeOfWorks = refs.scopeOfWorks || [];
    refs.customers = refs.customers || [];
    refs.statuses = refs.statuses || [];

    await addItem();
});

/* =====================================================
 * WATCHERS
 * ===================================================== */

watch(
    () => form.scope_of_work_id,
    async (newId) => {
        await fetchScopeIncluded(newId);
    },
);
</script>

<template>
    <div class="container py-4">
        <form @submit.prevent="submit" class="row g-3">
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
                                        (form.service_type_id = val
                                            ? val.id
                                            : null)
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
                                    (val) =>
                                        (form.location_id = val ? val.id : null)
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
                                    (val) =>
                                        (form.customer_id = val ? val.id : null)
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
                                    (val) =>
                                        (form.status_id = val ? val.id : null)
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
                        @click="addItem"
                    >
                        + Add Item
                    </button>
                </div>

                <div class="card-body">
                    <div
                        v-for="(item, index) in form.items"
                        :key="index"
                        class="row g-3 align-items-end mb-4 border-bottom pb-3"
                    >
                        <!-- Lot -->
                        <div class="col-lg-3">
                            <label class="form-label fw-semibold"
                                >Lot Selection</label
                            >
                            <Multiselect
                                v-model="item.lot"
                                :options="item.options.lots"
                                label="lot_no"
                                track-by="id"
                                placeholder="Select Lot"
                                @update:modelValue="() => onLotChange(index)"
                            />
                        </div>

                        <!-- Allocation -->
                        <div class="col-lg-3">
                            <label class="form-label fw-semibold"
                                >Allocation</label
                            >
                            <Multiselect
                                v-model="item.allocation"
                                :options="item.options.allocations"
                                label="name"
                                track-by="id"
                                placeholder="Select Allocation"
                                :disabled="!item.options.allocations.length"
                                @update:modelValue="
                                    () => onAllocationChange(index)
                                "
                            />
                        </div>

                        <!-- Owner -->
                        <div class="col-lg-3">
                            <label class="form-label fw-semibold">Owner</label>
                            <Multiselect
                                v-model="item.owner"
                                :options="item.options.owners"
                                label="name"
                                track-by="id"
                                placeholder="Select Owner"
                                :disabled="!item.options.owners.length"
                                @update:modelValue="() => onOwnerChange(index)"
                            />
                        </div>

                        <!-- Condition -->
                        <div class="col-lg-3">
                            <label class="form-label fw-semibold"
                                >Condition</label
                            >
                            <Multiselect
                                v-model="item.condition"
                                :options="item.options.conditions"
                                label="name"
                                track-by="id"
                                placeholder="Select Condition"
                                :disabled="!item.options.conditions.length"
                            />
                        </div>

                        <!-- Avail. Qty -->
                        <div class="col-lg-2">
                            <label class="form-label fw-semibold text-muted"
                                >Available Qty</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                :value="item.lot?.qty ?? 0"
                                readonly
                            />
                        </div>

                        <!-- Qty Required -->
                        <div class="col-lg-2">
                            <label class="form-label fw-semibold text-muted"
                                >Qty Required</label
                            >
                            <input
                                type="number"
                                class="form-control"
                                v-model.number="item.qtyRequired"
                                :max="item.lot?.qty ?? 0"
                                min="0"
                                placeholder="0"
                            />
                        </div>

                        <!-- Remove -->
                        <div class="col-lg-1 text-end">
                            <button
                                type="button"
                                class="btn btn-outline-danger btn-sm"
                                title="Remove Item"
                                @click="removeItem(index)"
                            >
                                ✕
                            </button>
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
