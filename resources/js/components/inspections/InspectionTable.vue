<script setup>
import InspectionTableRow from "./InspectionTableRow.vue";

defineProps({
    data: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>Request No</th>
                <th>Status</th>
                <th>Submitted At</th>
                <th>Total Items</th>
            </tr>
        </thead>

        <tbody>
            <InspectionTableRow v-for="row in data" :key="row.id" :row="row" />
        </tbody>
    </table>
</template>
