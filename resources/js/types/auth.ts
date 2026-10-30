export interface AuthUser {
    type: "teacher" | "student";
    id: number;
    name: string;
}