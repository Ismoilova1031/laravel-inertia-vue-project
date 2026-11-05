import type { SelectOption } from "./common";
import type { LessonListItem } from "./lesson";
import type { StudentForCourse } from "./student";

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
    lessons: LessonListItem[];
    students: StudentForCourse[];
}

export interface CourseWithLessonCount extends CourseBase {
    lessonsCount: number;
}
