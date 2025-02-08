type Props = {
    children?: React.ReactNode;
}

export function CustomCard({children}: Props) {
    return <div className="p-4 bg-white shadow rounded-lg border">
        {children}
    </div>;
}