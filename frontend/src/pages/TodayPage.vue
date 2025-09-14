<template>
  <q-page padding>
    <q-card class="q-pa-md">
      <q-card-section>
        <div class="text-h6">Mood Summary (Last 7 Days)</div>
      </q-card-section>

      <q-separator />

      <!-- Counts -->
      <q-card-section>
        <div class="text-subtitle1 q-mb-sm">Overall Counts</div>
        <q-list dense>
          <q-item v-for="(count, mood) in summary.counts" :key="mood">
            <q-item-section>{{ mood }}</q-item-section>
            <q-item-section side>{{ count }}</q-item-section>
          </q-item>
        </q-list>
      </q-card-section>

      <q-separator />

      <!-- Timeline -->
      <q-card-section>
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
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
export default {
  name: 'MoodSummaryCard',
  data() {
    return {
      summary: {
        counts: {},
        timeline: {},
      },
    }
  },
  mounted() {
    const start = '2025-09-01' // replace with dynamic dates later
    const end = '2025-09-07'

    this.$api
      .get(`/mood-entries/summary?start=${start}&end=${end}`)
      .then((res) => {
        this.summary = res.data
      })
      .catch((err) => {
        console.error('Error fetching summary', err)
      })
  },
}
</script>
