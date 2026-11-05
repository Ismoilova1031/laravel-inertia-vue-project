import { ref } from "vue";

export const CERTIFICATE_WIDTH = 1123;
export const CERTIFICATE_HEIGHT = 794;

type Kind = "pdf" | "png";

export function useCertificateExport(
    getElement: () => HTMLElement | null | undefined,
    filename: () => string,
) {
    const busy = ref<Kind | null>(null);
    const error = ref("");

    async function capture(): Promise<HTMLCanvasElement> {
        const element = getElement();
        if (!element) throw new Error("The certificate is not ready yet.");

        const { default: html2canvas } = await import("html2canvas");

        return html2canvas(element, {
            scale: 2,
            backgroundColor: "#ffffff",
            logging: false,
            useCORS: true,
            onclone: (doc) => {
                const frame = doc.querySelector<HTMLElement>(
                    "[data-certificate-frame]",
                );
                const scaler = doc.querySelector<HTMLElement>(
                    "[data-certificate-scaler]",
                );

                if (frame) {
                    frame.style.width = `${CERTIFICATE_WIDTH}px`;
                    frame.style.height = `${CERTIFICATE_HEIGHT}px`;
                    frame.style.overflow = "visible";
                }
                if (scaler) scaler.style.transform = "none";
            },
        });
    }

    async function run(kind: Kind, task: () => Promise<void>) {
        if (busy.value) return;

        busy.value = kind;
        error.value = "";

        try {
            await task();
        } catch (e) {
            error.value =
                e instanceof Error ? e.message : "Could not create the file.";
        } finally {
            busy.value = null;
        }
    }

    const downloadPng = () =>
        run("png", async () => {
            const canvas = await capture();
            const link = document.createElement("a");

            link.href = canvas.toDataURL("image/png");
            link.download = `${filename()}.png`;
            link.click();
        });

    const downloadPdf = () =>
        run("pdf", async () => {
            const canvas = await capture();
            const { jsPDF } = await import("jspdf");

            const pdf = new jsPDF({
                orientation: "landscape",
                unit: "px",
                format: [CERTIFICATE_WIDTH, CERTIFICATE_HEIGHT],
                compress: true,
                hotfixes: ["px_scaling"],
            });

            pdf.addImage(
                canvas.toDataURL("image/jpeg", 0.95),
                "JPEG",
                0,
                0,
                CERTIFICATE_WIDTH,
                CERTIFICATE_HEIGHT,
            );
            pdf.save(`${filename()}.pdf`);
        });

    return { busy, error, downloadPdf, downloadPng };
}
