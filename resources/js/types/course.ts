import { Lesson } from "./lesson";
import { Student } from "./student";
import { SelectOption } from "./common";

export interface CourseBase {
    id: number;
    title: string;
    description: string;
}

export interface Course extends CourseBase {
    category: string;
    status: string;
}

export interface CourseDetail extends Course {
    lessons: Lesson[];
    students: Student[];
}

export interface CourseEdit extends CourseBase {
    category: SelectOption;
    status: SelectOption;
}