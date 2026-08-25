import { SelectOption } from "./common";

export interface Lesson {
    id: number;
    title: string;
    description: string;
    lessonType: SelectOption;
    lessonContent: string | null;
    videoUrl: string | null;
    sortOrder: number;
}