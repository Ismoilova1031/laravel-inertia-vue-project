import { LessonType } from "@/types/lesson";

export const lessonTypeMeta: Record<number, { icon: string; color: string }> = {
    [LessonType.VIDEO]: { icon: "mdi-play-circle-outline", color: "red" },
    [LessonType.TEXT]: { icon: "mdi-text-box-outline", color: "blue" },
    [LessonType.TASK]: { icon: "mdi-clipboard-check-outline", color: "orange" },
};

export const fallbackLessonMeta = { icon: "mdi-book-outline", color: "grey" };