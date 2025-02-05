import {flexRender, getCoreRowModel, useReactTable} from "@tanstack/react-table";
import {Table} from "flowbite-react";
import {useMemo} from "react";

type Props = {
    columns: any;
    data: unknown[];
}

const CustomTable = ({columns, data}: Props) => {
    const table = useReactTable({data, columns, getCoreRowModel: getCoreRowModel()});

    const tableData = useMemo(() => {
        return (
            !data || data.length === 0 ? <div>No Data</div> : table.getRowModel().rows.map((row) => (
                <Table.Row key={row.id}>
                    {row.getVisibleCells().map((cell) => (
                        <Table.Cell key={cell.id}>
                            {flexRender(
                                cell.column.columnDef.cell,
                                cell.getContext()
                            )}
                        </Table.Cell>
                    ))}
                </Table.Row>
            ))
        )
    }, [table]);

    return (
        <Table striped>
            <Table.Head>
                {table.getHeaderGroups().map((headerGroup) =>
                    headerGroup.headers.map((header) => (
                        <Table.HeadCell colSpan={header.colSpan} key={header.id}>
                            {header.isPlaceholder ? null : (
                                typeof header.column.columnDef.header === "string" &&
                                header.column.columnDef.header.includes("\n") ? (
                                    <pre className="whitespace-pre-wrap text-center">
                            {header.column.columnDef.header}
                        </pre>
                                ) : (
                                    flexRender(header.column.columnDef.header, header.getContext())
                                )
                            )}
                        </Table.HeadCell>
                    ))
                )}
            </Table.Head>
            <Table.Body>
                {tableData}
            </Table.Body>
        </Table>
    )
}

export default CustomTable;