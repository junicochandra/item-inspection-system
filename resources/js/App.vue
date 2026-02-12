<template>
    <router-view />

    <div
        class="toast-container position-fixed top-0 end-0 p-3"
        style="z-index: 1100"
    >
        <div
            ref="toastEl"
            class="toast align-items-center text-white border-0"
            :class="toastClass"
            role="alert"
        >
            <div class="d-flex">
                <div class="toast-body">
                    {{ toastMessage }}
                </div>
                <button
                    type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"
                ></button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, provide, nextTick } from "vue";
import * as bootstrap from "bootstrap";

const toastEl = ref(null);
const toastMessage = ref("");
const toastClass = ref("bg-success");

const showToast = async (message, type = "success") => {
    toastMessage.value = message;

    switch (type) {
        case "success":
            toastClass.value = "bg-success";
            break;
        case "error":
            toastClass.value = "bg-danger";
            break;
        case "warning":
            toastClass.value = "bg-warning text-dark";
            break;
        default:
            toastClass.value = "bg-secondary";
    }

    await nextTick();

    const toast = new bootstrap.Toast(toastEl.value, {
        delay: 3000,
    });

    toast.show();
};

provide("showToast", showToast);
</script>
