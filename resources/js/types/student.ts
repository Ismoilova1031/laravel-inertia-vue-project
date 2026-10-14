export interface Student {
    id: number
    name: string
    surname: string
    fullName: string
    email: string
    username: string
}

export interface StudentForCourse extends Student {
    isActive: boolean;
}