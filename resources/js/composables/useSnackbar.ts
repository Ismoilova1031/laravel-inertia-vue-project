import { reactive } from "vue";

export function useSnackbar() {
    const snackbar = reactive({
        show: false,
        text: "",
        color: "success" as "success" | "error",
    });

    function notify(text: string, color: "success" | "error" = "success") {
        snackbar.text = text;
        snackbar.color = color;
        snackbar.show = true;
    }

    return { snackbar, notify };
}
