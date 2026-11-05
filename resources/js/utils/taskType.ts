import { FileExtensions } from "@/types/file-extensions";
import { TaskTypes } from "@/types/task";

export const taskTypeOptions = [
    { title: "Quiz", value: TaskTypes.QUIZ },
    { title: "File upload", value: TaskTypes.FILE_UPLOAD },
    { title: "Discussion", value: TaskTypes.DISCUSSION },
];

export const fileExtensionOptions = Object.values(FileExtensions).map((value) => ({
    title: value.toUpperCase(),
    value,
}));