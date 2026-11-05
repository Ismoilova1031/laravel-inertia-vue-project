export interface GradeStyle {
    title: string;
    color: string;
}

const grades: Record<string, GradeStyle> = {
    S: { title: "Outstanding", color: "#B8860B" },
    A: { title: "Excellent", color: "#1B7F4B" },
    B: { title: "Very good", color: "#1D5FBF" },
    C: { title: "Good", color: "#7B3FA0" },
    D: { title: "Satisfactory", color: "#51657A" },
};

export const completionStyle: GradeStyle = { title: "Completed", color: "#334155" };

export const gradeStyle = (grade: string | null): GradeStyle => (grade ? (grades[grade] ?? completionStyle) : completionStyle);