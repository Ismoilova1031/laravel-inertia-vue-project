const NOTIFICATION = { CERTIFICATE: 1, FILE_ACCEPTED: 2, FILE_REJECTED: 3, ANSWER_GRADED: 4 } as const;

export const notificationMeta: Record<number, { icon: string; color: string }> = {
    [NOTIFICATION.CERTIFICATE]: { icon: "mdi-certificate-outline", color: "success" },
    [NOTIFICATION.FILE_ACCEPTED]: { icon: "mdi-file-check-outline", color: "success" },
    [NOTIFICATION.FILE_REJECTED]: { icon: "mdi-file-alert-outline", color: "error" },
    [NOTIFICATION.ANSWER_GRADED]: { icon: "mdi-pencil-check-outline", color: "primary" },
};

export const fallbackNotificationMeta = { icon: "mdi-bell-outline", color: "grey" };