<script setup>
import { ref } from 'vue';
import Slider from '../ui/Slider.vue';
import FormInput from '../ui/FormInput.vue';
import Button from '../ui/Button.vue';
import useStatsStore from '../../store/useStatsStore';
import useHttpRequest from '../../composables/useHttpRequest';

const props = defineProps({
    show: {
        type: Boolean,
        default: () => false,
    }
});
const emit = defineEmits(['hide']);

const statsStore = useStatsStore();
const { index: fetchStats } = useHttpRequest('/stats');

const currentDate = new Date();
const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

const dateRange = ref({
    start: firstDay.toISOString().split('T')[0],
    end: lastDay.toISOString().split('T')[0]
});

const updateStats = async () => {
    const params = {
        start_date: dateRange.value.start,
        end_date: dateRange.value.end
    };

    console.log("Sending params:", params); // 🔍 Debug log

    const data = await fetchStats(params);
    if (data) {
        statsStore.$patch({ stats: data });
    }
};
</script>

<template>
    <Slider
        :show="show"
        title="Statistics Filter"
        @hide="emit('hide')"
    >
        <div class="mt-4 space-y-4">
            <div class="flex flex-col gap-4">
                <FormInput
                    type="date"
                    v-model="dateRange.start"
                    label="Start Date"
                    @change="updateStats"
                />
                
                <FormInput
                    type="date"
                    v-model="dateRange.end"
                    label="End Date"
                    @change="updateStats"
                />

                <Button
                    title="Apply Filter"
                    class="!w-full"
                    @click="updateStats"
                />
            </div>
        </div>
    </Slider>
</template>
