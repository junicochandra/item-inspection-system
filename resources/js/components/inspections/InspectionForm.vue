<script setup>
import { reactive, ref, watch, onMounted } from "vue";
import axios from "axios";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import { useInspectionReferences } from "@/composables/useInspectionReferences";

// Form reactive
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

// Scope Included (tags display)
const scopeIncludeds = ref([]);

// Load references from API
const { refs, load } = useInspectionReferences();
onMounted(async () => {
    await load();

    // Pastikan refs selalu array
    refs.locations = refs.locations || [];
    refs.serviceTypes = refs.serviceTypes || [];
    refs.scopeOfWorks = refs.scopeOfWorks || [];
    refs.customers = refs.customers || [];
    refs.statuses = refs.statuses || [];
});

watch(
    () => form.scope_of_work_id,
    async (newId) => {
        if (!newId) {
            scopeIncludeds.value = [];
            return;
        }

        try {
            const { data } = await axios.get(`/api/scope-included/${newId}`);
            if (data.success) {
                scopeIncludeds.value = data.data;
            } else {
                scopeIncludeds.value = [];
            }
        } catch (error) {
            console.error(error);
            scopeIncludeds.value = [];
        }
    },
);

// Submit form
const submit = async () => {
    try {
        await axios.post("/api/inspections", form);
        alert("Inspection created");
    } catch (error) {
        console.error(error);
        alert("Failed to create inspection");
    }
};

const mapToId = (value) => value?.id || null;
</script>

<template>
    <div class="container mt-4 mb-4">
        <form @submit.prevent="submit" class="row g-3">
            <div class="col-md-12">
                <h3>Create Inspection</h3>
            </div>

            <!-- Request No -->
            <div class="col-md-12">
                <label class="form-label">Request No</label>
                <input v-model="form.request_no" class="form-control" />
            </div>

            <!-- Service Type -->
            <div class="col-md-6">
                <label class="form-label">Service Type</label>
                <Multiselect
                    :options="refs.serviceTypes || []"
                    label="name"
                    track-by="id"
                    placeholder="-- select --"
                    :searchable="true"
                    :close-on-select="true"
                    :model-value="
                        (refs.serviceTypes || []).find(
                            (s) => s.id === form.service_type_id,
                        ) || null
                    "
                    @update:model-value="
                        (val) => (form.service_type_id = mapToId(val))
                    "
                />
            </div>

            <!-- Scope of Work -->
            <div class="col-md-6">
                <label class="form-label">Scope of Work</label>
                <Multiselect
                    :options="refs.scopeOfWorks || []"
                    label="name"
                    track-by="id"
                    placeholder="-- select --"
                    :searchable="true"
                    :close-on-select="true"
                    :model-value="
                        (refs.scopeOfWorks || []).find(
                            (s) => s.id === form.scope_of_work_id,
                        ) || null
                    "
                    @update:model-value="
                        (val) => (form.scope_of_work_id = val?.id || null)
                    "
                />
            </div>

            <!-- Scope Included (readonly tags) -->
            <div class="col-md-12">
                <label class="form-label">Scope Included</label>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    <span
                        v-for="included in scopeIncludeds"
                        :key="included.id"
                        class="badge bg-secondary"
                    >
                        {{ included.name }}
                    </span>
                    <span
                        v-if="!scopeIncludeds.length"
                        class="badge bg-secondary"
                    >
                        -
                    </span>
                </div>
            </div>

            <!-- Location -->
            <div class="col-md-3">
                <label class="form-label">Location</label>
                <Multiselect
                    :options="refs.locations || []"
                    label="name"
                    track-by="id"
                    placeholder="-- select --"
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

            <!-- Estimated Completion Date -->
            <div class="col-md-3">
                <label class="form-label">Estimated Completion Date</label>
                <input
                    type="date"
                    v-model="form.estimated_completion_date"
                    class="form-control"
                />
            </div>

            <!-- Customer -->
            <div class="col-md-3">
                <label class="form-label">Customer</label>
                <Multiselect
                    :options="refs.customers || []"
                    label="name"
                    track-by="id"
                    placeholder="-- optional --"
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
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <Multiselect
                    :options="refs.statuses || []"
                    label="label"
                    track-by="id"
                    placeholder="-- select --"
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

            <!-- Order Information -->
            <div class="col-md-12">
                <h3>Order Information</h3>
            </div>

            <div class="col-md-11">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Lot Selection</label>
                        <Multiselect
                            :options="refs.statuses || []"
                            label="label"
                            track-by="id"
                            placeholder="-- select --"
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

                    <div class="col-md-3">
                        <label class="form-label">Allocation</label>
                        <Multiselect
                            :options="refs.statuses || []"
                            label="label"
                            track-by="id"
                            placeholder="-- select --"
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

                    <div class="col-md-3">
                        <label class="form-label">Owner</label>
                        <Multiselect
                            :options="refs.statuses || []"
                            label="label"
                            track-by="id"
                            placeholder="-- select --"
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

                    <div class="col-md-3">
                        <label class="form-label">Condition</label>
                        <Multiselect
                            :options="refs.statuses || []"
                            label="label"
                            track-by="id"
                            placeholder="-- select --"
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
            <div class="col-md-1">
                <label class="form-label"></label>
                <button>+</button>
            </div>

            <!-- Note To Yard -->
            <div class="col-md-12 mt-3">
                <h3>Note To Yard</h3>
            </div>

            <!-- Note -->
            <div class="col-12">
                <label class="form-label">Note</label>
                <textarea v-model="form.note" class="form-control"></textarea>
            </div>

            <!-- Submit -->
            <div class="col-12">
                <button class="btn btn-primary">Submit</button>
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
