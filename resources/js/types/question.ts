export const QuestionType = {
    SINGLE_CHOICE: 1,
    MULTIPLE_SELECT: 2,
    SHORT_ANSWER: 3,
    OPEN_ENDED: 4,
} as const;

export type QuestionType = (typeof QuestionType)[keyof typeof QuestionType];