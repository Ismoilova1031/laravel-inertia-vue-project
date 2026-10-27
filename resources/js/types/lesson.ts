import type { SelectOption } from "./common";

export interface LessonRef {
    id: number;
    title: string;
}
export interface Lesson extends LessonRef {
    description: string;
    lesson_type: SelectOption;
    lesson_content: string | null;
    video_url: string | null;
    video_src: string | null;
    sort_order: number;

    task: {
        task_type: SelectOption | null;
        allowed_file_extensions: null;
        questions: {};
    };
}

export enum LessonType {
    VIDEO = 1,
    TEXT = 2,
    TASK = 3,
}
