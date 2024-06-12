import { Source } from "./source";
import { URL } from "./url";

export interface Checklist {
    id: string,
    name: string,
    createdAt: string,
    publishedIn: Source[],
    urls: URL[]
}