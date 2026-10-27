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
    task: LearnTask | null
    course: LessonRef;
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
    type: { value: number; label: string };
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
    status: { value: number; label: string };
    score: number | null;
    content: string | null;
    hasFile: boolean;
    teacherComment: string | null;
    submittedAt: string;
    answers: LearnAnswer[];
}

export interface LearnTask {
    id: number;
    type: { value: number; label: string };
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