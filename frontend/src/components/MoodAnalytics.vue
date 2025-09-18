<template>
  <q-card class="q-pa-md">
    <q-card-section>
      <div class="text-h6 q-mb-md">Mood Summary (Last 7 Days)</div>
    </q-card-section>

    <q-separator />

    <!-- Overall Counts -->
    <q-card-section>
      <div class="text-subtitle1 q-mb-sm">Overall Counts</div>
      <q-list dense>
        <q-item v-for="(count, mood) in summary.counts" :key="mood">
          <q-item-section>
            <div class="text-subtitle2">{{ mood }}</div>
            <q-linear-progress
              :value="count / Math.max(...Object.values(summary.counts))"
              rounded
              class="q-mt-xs"
              :style="{ '--q-color': getMoodColor(mood) }"
            />
          </q-item-section>
          <q-item-section side>
            <div class="text-body2">{{ count }}</div>
          </q-item-section>
        </q-item>
      </q-list>
    </q-card-section>

    <q-separator />

    <!-- Daily Timeline -->
    <!-- <q-card-section>
      <div class="text-subtitle1 q-mb-sm">Daily Timeline</div>
      <q-list bordered separator>
        <q-item v-for="(slots, date) in summary.timeline" :key="date">
          <q-item-section>
            <div class="text-bold">{{ date }}</div>
            <div class="text-caption">
              Morning: {{ slots.morning }} | Afternoon: {{ slots.afternoon }} | Night:
              {{ slots.night }}
            </div>
          </q-item-section>
        </q-item>
      </q-list>
    </q-card-section> -->
  </q-card>
</template>

<script>
export default {
  name: 'MoodAnalytics',
  data() {
    return {
      summary: {
        counts: {},
        timeline: {},
      },
    }
  },
  methods: {
    getMoodColor(mood) {
      const moodColors = {
        happy: '#ffeb3b',
        sad: '#2196f3',
        angry: '#f44336',
        relaxed: '#4caf50',
        excited: '#ff9800',
      }
      return moodColors[mood] || '#9e9e9e'
    },
  },
  mounted() {
    const start = '2025-09-01'
    const end = '2025-09-07'
    this.$api
      .get(`/mood-entries/summary?start=${start}&end=${end}`)
      .then((res) => (this.summary = res.data))
      .catch((err) => console.error('Error fetching summary', err))
  },
}
</script>

<style scoped lang="scss">
.q-linear-progress {
  background-color: var(--q-color) !important;

  :deep(.q-linear-progress__track) {
    background-color: var(--q-color) !important;
  }
}
</style>
