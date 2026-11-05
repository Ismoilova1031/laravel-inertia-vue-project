import type { SelectOption } from "./common";

export const TaskTypes = { QUIZ: 1, FILE_UPLOAD: 2, DISCUSSION: 3 } as const;
export type TaskType = (typeof TaskTypes)[keyof typeof TaskTypes];

export interface OptionPayload {
    id: number;
    option: string;
    is_correct: boolean;
}

export interface QuestionPayload {
    id: number;
    question: string;
    question_type: SelectOption;
    points: number;
    correct_answer: string | null;
    options?: OptionPayload[];
}

export interface TaskPayload {
    id: number;
    task_type: SelectOption;
    allowed_file_extensions: string[] | null;
    questions?: QuestionPayload[];
}
