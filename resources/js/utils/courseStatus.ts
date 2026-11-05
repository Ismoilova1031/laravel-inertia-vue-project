export const COURSE_STATUS = { DRAFT: 0, PUBLISHED: 1, ARCHIVED: 2 } as const;

export const courseTabs = [
    { value: COURSE_STATUS.DRAFT, label: "Draft", empty: "No draft courses" },
    {
        value: COURSE_STATUS.PUBLISHED,
        label: "Published",
        empty: "No published courses",
    },
    {
        value: COURSE_STATUS.ARCHIVED,
        label: "Archived",
        empty: "No archived courses",
    },
] as const;

export const courseStatusColor: Record<number, string> = {
    [COURSE_STATUS.DRAFT]: "grey",
    [COURSE_STATUS.PUBLISHED]: "success",
    [COURSE_STATUS.ARCHIVED]: "warning",
};

export const courseActionMeta: Record<
    number,
    {
        icon: string;
        color: string;
        title: string;
        message: string;
        confirm: string;
        target: number | null;
    }
> = {
    [COURSE_STATUS.DRAFT]: {
        icon: "mdi-delete",
        color: "error",
        title: "Delete Course",
        message: "This action cannot be undone.",
        confirm: "Delete",
        target: null,
    },
    [COURSE_STATUS.PUBLISHED]: {
        icon: "mdi-archive-outline",
        color: "warning",
        title: "Archive Course",
        message:
            "New students will not be able to enroll. Existing results are kept.",
        confirm: "Archive",
        target: COURSE_STATUS.ARCHIVED,
    },
    [COURSE_STATUS.ARCHIVED]: {
        icon: "mdi-publish",
        color: "success",
        title: "Republish Course",
        message: "The course will become available for enrollment again.",
        confirm: "Republish",
        target: COURSE_STATUS.PUBLISHED,
    },
};
