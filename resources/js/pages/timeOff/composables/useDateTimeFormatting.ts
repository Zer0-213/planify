import { format, parseISO } from 'date-fns';

export function useDateTimeFormatting() {
    // Helper function to format date for input[type="date"]
    const formatDateForInput = (dateString?: string | null): string => {
        if (!dateString) return '';

        try {
            if (/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
                return dateString;
            }
            return format(parseISO(dateString), 'yyyy-MM-dd');
        } catch {
            return dateString || '';
        }
    };

    // Helper function to format time for input[type="time"]
    const formatTimeForInput = (timeString?: string | null): string => {
        if (!timeString) return '';

        try {
            if (/^\d{2}:\d{2}$/.test(timeString)) {
                return timeString;
            }
            if (timeString.includes('T') || timeString.includes(' ')) {
                return format(parseISO(timeString), 'HH:mm');
            }
            if (/^\d{2}:\d{2}:\d{2}$/.test(timeString)) {
                return timeString.substring(0, 5);
            }
            return timeString;
        } catch {
            return timeString || '';
        }
    };

    // Helper function to format date for display (dd/MM/yyyy)
    const formatDateForDisplay = (dateString?: string | null): string => {
        if (!dateString) return '';

        try {
            return format(parseISO(dateString), 'dd/MM/yyyy');
        } catch {
            return dateString || '';
        }
    };

    // Helper function to get current date in input format
    const getCurrentDateForInput = (): string => {
        return format(new Date(), 'yyyy-MM-dd');
    };

    return {
        formatDateForInput,
        formatTimeForInput,
        formatDateForDisplay,
        getCurrentDateForInput,
    };
}
