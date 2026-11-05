import { reactive } from "vue";

export type Notify = (text: string, color?: "success" | "error") => void;

export function useSnackbar() {
    const snackbar = reactive({ show: false, text: "", color: "success" as "success" | "error" });

    const notify: Notify = (text, color = "success") => {
        snackbar.text = text;
        snackbar.color = color;
        snackbar.show = true;
    };

    return { snackbar, notify };
}