<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { ChevronDown, ChevronLeft, ChevronRight, ChevronUp } from 'lucide-vue-next';

interface Props {
    direction?: 'right' | 'left' | 'up' | 'down';
}

const props = withDefaults(defineProps<Props>(), {
    direction: 'right'
});

const chevronIcon = {
    right: ChevronRight,
    left: ChevronLeft,
    up: ChevronUp,
    down: ChevronDown
};
</script>

<template>
    <Button size="icon" variant="outline">
        <component :is="chevronIcon[props.direction]" class="w-4 h-4" />
    </Button>
</template>
