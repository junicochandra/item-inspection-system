<script setup>
defineProps({
    row: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <tr>
        <td>{{ row.request_no }}</td>
        <td>{{ row.status?.label }}</td>
        <td>{{ row.submitted_at }}</td>
        <td>{{ row.items_count }}</td>
    </tr>
</template>
