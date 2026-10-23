export interface LearnStudent {
    id: number;
    fullName: string;
}

export interface LearnProgress {
    total: number;
    completed: number;
    percent: number;
}

export interface LearnCourseItem {
    id: number;
    title: string;
    description: string;
    category: string;
    status: { value: number; label: string };
    isActive: boolean;
    progress: LearnProgress;
    nextLessonId: number | null;
}

export interface LearnLessonItem {
    id: number;
    title: string;
    description: string;
    type: { value: number; label: string };
    completed: boolean;
}

export interface LearnCourse {
    id: number;
    title: string;
    description: string;
    category: string;
    isActive: boolean;
    progress: LearnProgress;
    nextLessonId: number | null;
    lessons: LearnLessonItem[];
}

export interface LessonRef {
    id: number;
    title: string;
}

export interface LearnLesson {
    id: number;
    title: string;
    description: string;
    type: { value: number; label: string };
    content: string | null;
    videoUrl: string | null;
    completed: boolean;
    course: LessonRef;
    previous: LessonRef | null;
    next: LessonRef | null;
}