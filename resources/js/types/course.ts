import type { Lesson } from "./lesson";
import type { StudentForCourse } from "./student";
import type { SelectOption } from "./common";

export interface CourseRef {
    id: number;
    title: string;
}

export interface CourseBase extends CourseRef {
    description: string;
}

export interface Course extends CourseBase {
    category: string;
    status: SelectOption;
}

export interface CourseDetail extends CourseBase {
    category: SelectOption;
    status: SelectOption;
    lessons: Lesson[];
    students: StudentForCourse[];
}
