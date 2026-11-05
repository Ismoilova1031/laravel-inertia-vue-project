export interface StudentSummary {
    id: number;
    fullName: string;
}

export interface Student extends StudentSummary {
    name: string;
    surname: string;
    email: string;
    username: string;
}

export interface StudentForCourse extends Student {
    isActive: boolean;
}
