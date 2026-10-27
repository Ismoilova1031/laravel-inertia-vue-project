import type { SelectOption } from "./common";
import type { Course, CourseRef } from "./course";
import type { LessonRef } from "./lesson";


export interface LearnProgress {
    total: number;
    completed: number;
    percent: number;
}

export interface LearnCourseItem extends Course {
    isActive: boolean;
    progress: LearnProgress;
    nextLessonId: number | null;
}

export interface LearnLessonItem extends LessonRef {
    description: string;
    type: SelectOption;
    completed: boolean;
}

export interface LearnCourse extends LearnCourseItem {
    lessons: LearnLessonItem[];
}

export interface LearnLesson extends LearnLessonItem {
    content: string | null;
    videoUrl: string | null;
    task: LearnTask | null
    course: CourseRef;
    previous: LessonRef | null;
    next: LessonRef | null;
}

export interface LearnOption {
    id: number;
    text: string;
    isCorrect?: boolean;
}

export interface LearnQuestion {
    id: number;
    text: string;
    type: SelectOption;
    points: number;
    options: LearnOption[];
    correctAnswer: string | null;
}

export interface LearnAnswer {
    questionId: number;
    selectedOptionIds: number[];
    text: string | null;
    score: number | null;
    teacherComment: string | null;
}

export interface LearnSubmission {
    attempt: number;
    status: SelectOption;
    score: number | null;
    content: string | null;
    hasFile: boolean;
    teacherComment: string | null;
    submittedAt: string;
    answers: LearnAnswer[];
}

export interface LearnTask {
    id: number;
    type: SelectOption;
    allowedExtensions: string[];
    questions: LearnQuestion[];
    canSubmit: boolean;
    blockedReason: string | null;
    attemptsLeft: number;
    bestScore: number | null;
    maxScore: number;
    revealAnswers: boolean;
    submissions: LearnSubmission[];
}

export interface QuizPayload {
    answers: Record<number, { options: number[]; text: string }>;
}