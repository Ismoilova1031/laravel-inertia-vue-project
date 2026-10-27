import type { SelectOption } from "./common";
import type { LessonRef } from "./lesson";
import type { StudentSummary } from "./student";

export interface SubmissionAnswerRow {
    questionId: number;
    question: string;
    questionType: number;
    points: number;
    text: string | null;
    selected: string[];
    score: number | null;
    teacherComment: string | null;
    needsGrading: boolean;
}

export interface SubmissionRow {
    id: number;
    attempt: number;
    status: SelectOption;
    score: number | null;
    maxScore: number;
    submittedAt: string;
    student: StudentSummary;
    lesson: LessonRef;
    taskType: SelectOption;
    content: string | null;
    hasFile: boolean;
    teacherComment: string | null;
    answers: SubmissionAnswerRow[];
    fileExtension: string | null;
}