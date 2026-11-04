export const notificationMeta: Record<number, { icon: string; color: string }> =
    {
        1: { icon: "mdi-certificate-outline", color: "success" },
        2: { icon: "mdi-file-check-outline", color: "success" },
        3: { icon: "mdi-file-alert-outline", color: "error" },
        4: { icon: "mdi-pencil-check-outline", color: "primary" },
    };

export const fallbackNotificationMeta = {
    icon: "mdi-bell-outline",
    color: "grey",
};
