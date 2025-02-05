import type {JSX} from "react";

export type Columns = {
    accessorKey: string;
    header: string;
    cell?: (row: any) => JSX.Element;
}