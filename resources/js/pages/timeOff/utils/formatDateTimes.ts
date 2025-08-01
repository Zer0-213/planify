import { format, parseISO } from 'date-fns';

export const formatTimeRange = (start: string | null, end: string | null) => {
    if (!start || !end) return 'N/A';

    if (start.includes(':') && start.length <= 8) {
        const startTime = start.substring(0, 5); // Get HH:MM part
        const endTime = end.substring(0, 5); // Get HH:MM part
        return `${startTime} - ${endTime}`;
    }

    try {
        const startFormatted = format(parseISO(start), 'HH:mm');
        const endFormatted = format(parseISO(end), 'HH:mm');
        return `${startFormatted} - ${endFormatted}`;
    } catch {
        return `${start} - ${end}`;
    }
};

export const formatDateRange = (start: string | null, end: string | null) =>
    start && end ? `${format(start, 'dd/MM/yyyy')} - ${format(end, 'dd/MM/yyyy')}` : 'N/A';
