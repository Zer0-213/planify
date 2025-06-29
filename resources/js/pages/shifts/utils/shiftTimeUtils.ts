// resources/js/pages/shifts/utils/shiftTimeUtils.ts
import { format, parseISO } from 'date-fns';

type Format = string;

export function formatShiftTime(isoTime: string | null | undefined, formatString?: Format): string {
    if (!isoTime) return '';

    return format(parseISO(isoTime), formatString || 'yyyy-MM-dd HH:mm:ss');
}

export function extractTimeFromIso(isoString?: string): string {
    if (!isoString) return '';
    const date = new Date(isoString);
    return date.toLocaleTimeString('default', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
    });
}
