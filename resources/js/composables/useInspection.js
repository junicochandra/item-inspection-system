import { ref } from "vue";
import { inspectionService } from "@/services/inspectionService";

export function useInspection() {
    const inspections = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const loadInspections = async () => {
        loading.value = true;
        error.value = null;

        try {
            inspections.value = await inspectionService.fetchList();
        } catch (err) {
            error.value = err;
        } finally {
            loading.value = false;
        }
    };

    return {
        inspections,
        loading,
        error,
        loadInspections,
    };
}
