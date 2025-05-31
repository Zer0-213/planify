<script lang="ts" setup>
import Multiselect from 'vue-multiselect';
import { defineEmits, defineProps } from 'vue';
import { Option, OptionList } from '@/types/options';


const props = defineProps<{
    options: OptionList;
    selectedOptions: OptionList;
}>();


const selectedOptionsLabels = props.selectedOptions.map(option => option.label);

const emit = defineEmits<{
    (event: 'update-value', selectedOption: Option): void;
}>();
</script>

<template>
    <div class="w-full">
        <multiselect
            v-model="selectedOptionsLabels"
            :close-on-select="true"
            :multiple="true"
            :options="options"
            :value="selectedOptions"
            class="w-full"
            label="label"
            placeholder="Select options"
            track-by="value"
            @remove="(selected:any) => emit('update-value', selected)"
            @select="(selected:any) => emit('update-value', selected)"
        />
    </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
