export const createColumns = (week: Date) => {
    const daysOfWeek = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
    return daysOfWeek.map((day, index) => {
        const date = new Date(week);
        date.setDate(week.getDate() + index);
        return {
            accessorKey: day.toLowerCase(),
            header: `${day}\n(${date.toLocaleDateString()})`,
        };
    });
}

