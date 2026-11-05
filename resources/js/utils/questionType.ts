import { QuestionType } from "@/types/question";

export const questionTypeOptions = [
    { title: "Single Choice", value: QuestionType.SINGLE_CHOICE },
    { title: "Multiple Select", value: QuestionType.MULTIPLE_SELECT },
    { title: "Short Answer", value: QuestionType.SHORT_ANSWER },
    { title: "Open Ended", value: QuestionType.OPEN_ENDED },
];

export const questionTypeLabel = (value: number): string =>
    questionTypeOptions.find((o) => o.value === value)?.title ?? "Unknown";
