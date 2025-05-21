import { TimeUpdatePayload } from '@/pages/shifts/types/timeUpdate';
import { ref, watch } from 'vue';

export function useShiftTime(startTime: string, endTime: string, disabled: boolean | undefined, emit: (payload: TimeUpdatePayload) => void) {
    const extractTimeFromIso = (isoString?: string): string => isoString?.slice(11, 16) || '';

    const localStart = ref(extractTimeFromIso(startTime));
    const localEnd = ref(extractTimeFromIso(endTime));

    const updateAndEmitTime = (timeString: string, originalDate: string | undefined, timeField: 'starts_at' | 'ends_at') => {
        if (!timeString || disabled) return;

        const [hours, minutes] = timeString.split(':').map(Number);
        const newDate = originalDate ? new Date(originalDate) : new Date();

        newDate.setHours(hours);
        newDate.setMinutes(minutes);

        emit({ [timeField]: newDate.toISOString() });
    };

    watch(localStart, (newValue) => updateAndEmitTime(newValue, startTime, 'starts_at'));
    watch(localEnd, (newValue) => updateAndEmitTime(newValue, endTime, 'ends_at'));

    return {
        localStart,
        localEnd,
    };
}
