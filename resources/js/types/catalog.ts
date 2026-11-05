import type { RatingSummary } from "./common";

export interface CatalogCourse {
    id: number;
    title: string;
    description: string;
    category: string;
    liked: boolean;
    planned: boolean;
    enrolled: boolean;
    rating: RatingSummary;
}

export type InterestKind = "like" | "planned";