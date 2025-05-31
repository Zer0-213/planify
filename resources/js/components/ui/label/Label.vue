<script lang="ts" setup>
import { cn } from '@/lib/utils';
import { Label, type LabelProps } from 'reka-ui';
import { computed, type HTMLAttributes } from 'vue';

const props = withDefaults(defineProps<LabelProps & { class?: HTMLAttributes['class'], required?: boolean }>(), {
    required: false
});

const delegatedProps = computed(() => {
    const { class: _, ...delegated } = props;

    return delegated;
});
</script>

<template>
    <Label
        :class="
      cn(
        'flex items-center text-sm leading-none font-medium select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50',
        props.class,
      )
    "
        data-slot="label"
        v-bind="delegatedProps"
    >
        <slot />
        <span v-if="required" class="text-red-500">*</span>
    </Label>
</template>
