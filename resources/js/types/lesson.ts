import type { SelectOption } from "./common";
import type { TaskPayload } from "./task";

export const LessonType = { VIDEO: 1, TEXT: 2, TASK: 3 } as const;
export type LessonType = (typeof LessonType)[keyof typeof LessonType];

export interface LessonRef {
    id: number;
    title: string;
}

export interface LessonListItem extends LessonRef {
    description: string;
    lessonType: SelectOption;
    lessonContent: string | null;
    videoUrl: string | null;
    sortOrder: number;
}

export interface Lesson extends LessonRef {
    description: string;
    lesson_type: SelectOption;
    lesson_content: string | null;
    video_url: string | null;
    sort_order: number;
    task: TaskPayload | null;
}
