export const SUBMISSION_STATUS = {
    PENDING: 0,
    ACCEPTED: 1,
    REJECTED: 2,
} as const;

export const submissionStatusColor = (status: number): string =>
    status === SUBMISSION_STATUS.ACCEPTED
        ? "success"
        : status === SUBMISSION_STATUS.REJECTED
          ? "error"
          : "warning";
