import { Lesson } from "./lesson";
import { Student } from "./student";

export interface Course {
    id: number;
    title: string;
    description: string;
    category: string;
    status: string;
    lessons: Lesson[];
    students: Student[];
}