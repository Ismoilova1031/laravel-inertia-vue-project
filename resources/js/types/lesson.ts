export interface Lesson {
    id: number;
    title: string;
    description: string;
    lessonType: number;
    lessonContent: string | null;
    videoUrl: string | null;
    sortOrder: number;
}