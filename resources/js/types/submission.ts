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
    status: { value: number; label: string };
    score: number | null;
    maxScore: number;
    submittedAt: string;
    student: { id: number; fullName: string };
    lesson: { id: number; title: string };
    taskType: { value: number; label: string };
    content: string | null;
    hasFile: boolean;
    teacherComment: string | null;
    answers: SubmissionAnswerRow[];
    fileExtension: string | null;
}