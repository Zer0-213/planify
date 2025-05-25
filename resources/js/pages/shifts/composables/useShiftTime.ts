import { ShiftTime } from '@/pages/shifts/types/shiftTypes';
import { format } from 'date-fns';
import { ref, watch } from 'vue';

type Props = {
    date: string;
    startTime: string;
    endTime: string;
    emit: (payload: ShiftTime) => void;
};

export function useShiftTime({ date, startTime, endTime, emit }: Props) {
    const extractTimeFromIso = (isoString?: string): string => {
        if (!isoString) return '';
        const date = new Date(isoString);
        return date.toLocaleTimeString('default', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
        });
    };

    const localStart = ref(extractTimeFromIso(startTime));
    const localEnd = ref(extractTimeFromIso(endTime));

    const updateAndEmitTime = (timeString: string, timeField: 'starts_at' | 'ends_at') => {
        if (!timeString) return;

        const [hours, minutes] = timeString.split(':').map(Number);

        const baseDate = date ? new Date(date) : new Date();

        const newDate = new Date(baseDate);
        newDate.setHours(hours, minutes, 0, 0);

        emit({ [timeField]: newDate.toISOString(), shift_date: format(baseDate, 'yyyy-MM-dd') });
    };

    watch(localStart, (newValue) => updateAndEmitTime(newValue, 'starts_at'));
    watch(localEnd, (newValue) => updateAndEmitTime(newValue, 'ends_at'));

    return {
        localStart,
        localEnd,
    };
}
