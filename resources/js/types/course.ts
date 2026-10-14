import type { Lesson } from "./lesson";
import type { StudentForCourse } from "./student";
import type { SelectOption } from "./common";

export interface CourseBase {
    id: number;
    title: string;
    description: string;
}

export interface Course extends CourseBase {
    category: string;
    status: string;
}

export interface CourseDetail extends CourseBase {
    category: SelectOption;
    status: SelectOption;
    lessons: Lesson[];
    students: StudentForCourse[];
}

