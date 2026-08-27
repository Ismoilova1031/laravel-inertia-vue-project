import { SelectOption } from "./common";
export interface Lesson {
    id: number;
    title: string;
    description: string;
    lesson_type: SelectOption;
    lesson_content: string | null;
    video_url: string | null;
    video_src: string | null;
    sort_order: number;

    task: {
            task_type: SelectOption | null,
            deadline: null,
            file_extensions: null,
            questions: {},
        };
}