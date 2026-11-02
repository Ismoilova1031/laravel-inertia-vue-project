export interface CatalogCourse {
    id: number;
    title: string;
    description: string;
    category: string;
    liked: boolean;
    planned: boolean;
    enrolled: boolean;
    rating: { average: number; count: number };
}

export type InterestKind = "like" | "planned";