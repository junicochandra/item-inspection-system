import axios from "axios";

export const inspectionService = {
    async fetchList() {
        const response = await axios.get("/api/inspections");
        return response.data.data;
    },
};
