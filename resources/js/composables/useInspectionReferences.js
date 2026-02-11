import { ref } from "vue";
import axios from "axios";

export function useInspectionReferences() {
    const refs = ref({});
    const loading = ref(false);

    const load = async () => {
        loading.value = true;
        const res = await axios.get("/api/inspection-references");
        refs.value = res.data;
        loading.value = false;
    };

    return { refs, load, loading };
}
