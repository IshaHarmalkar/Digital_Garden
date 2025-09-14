<template>
  <div class="mood-analytics q-pa-md">
    <div class="text-h4 q-mb-lg text-center">
      <q-icon name="mood" class="q-mr-sm" />
      Mood Analytics - Last 7 Days
    </div>

    <div v-if="loading" class="flex flex-center q-py-xl">
      <q-spinner-dots size="50px" color="primary" />
    </div>

    <div v-else-if="error" class="text-center q-py-xl">
      <q-icon name="error" size="48px" color="negative" />
      <div class="text-h6 q-mt-md text-negative">{{ error }}</div>
      <q-btn @click="fetchMoodData" color="primary" class="q-mt-md" label="Retry" icon="refresh" />
    </div>

    <div v-else-if="moodData" class="analytics-content">
      <!-- Overview Cards -->
      <div class="row q-gutter-md q-mb-xl">
        <div class="col-12 col-md-3">
          <q-card class="text-center q-pa-md bg-positive text-white">
            <div class="text-h3">{{ totalEntries }}</div>
            <div class="text-subtitle1">Total Entries</div>
          </q-card>
        </div>
        <div class="col-12 col-md-3">
          <q-card class="text-center q-pa-md bg-info text-white">
            <div class="text-h3">{{ dominantMood.name }}</div>
            <div class="text-subtitle1">Most Common</div>
            <div class="text-caption">{{ dominantMood.count }} times</div>
          </q-card>
        </div>
        <div class="col-12 col-md-3">
          <q-card class="text-center q-pa-md bg-secondary text-white">
            <div class="text-h3">{{ bestTimeSlot.name }}</div>
            <div class="text-subtitle1">Most Active Time</div>
            <div class="text-caption">{{ bestTimeSlot.count }} entries</div>
          </q-card>
        </div>
        <div class="col-12 col-md-3">
          <q-card class="text-center q-pa-md bg-accent text-white">
            <div class="text-h3">{{ positiveRatio }}%</div>
            <div class="text-subtitle1">Positive Moods</div>
          </q-card>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="row q-gutter-md q-mb-xl">
        <!-- Mood Distribution Chart -->
        <div class="col-12 col-md-6">
          <q-card class="full-height">
            <q-card-section>
              <div class="text-h6 q-mb-md">
                <q-icon name="pie_chart" class="q-mr-sm" />
                Mood Distribution
              </div>
              <canvas ref="moodChart" width="400" height="300"></canvas>
            </q-card-section>
          </q-card>
        </div>

        <!-- Time Slot Analysis -->
        <div class="col-12 col-md-6">
          <q-card class="full-height">
            <q-card-section>
              <div class="text-h6 q-mb-md">
                <q-icon name="schedule" class="q-mr-sm" />
                Mood by Time of Day
              </div>
              <canvas ref="timeChart" width="400" height="300"></canvas>
            </q-card-section>
          </q-card>
        </div>
      </div>

      <!-- Timeline -->
      <q-card class="q-mb-lg">
        <q-card-section>
          <div class="text-h6 q-mb-md">
            <q-icon name="timeline" class="q-mr-sm" />
            Daily Mood Timeline
          </div>
          <div class="timeline-container">
            <div v-for="(day, date) in moodData.timeline" :key="date" class="timeline-day q-mb-md">
              <div class="text-weight-bold q-mb-sm">
                {{ formatDate(date) }}
              </div>
              <div class="row q-gutter-sm">
                <div v-for="(mood, timeSlot) in day" :key="timeSlot" class="timeline-entry">
                  <q-chip
                    :color="getMoodColor(mood)"
                    :icon="getMoodIcon(mood)"
                    :label="`${timeSlot}: ${mood}`"
                    text-color="white"
                  />
                </div>
              </div>
            </div>
          </div>
        </q-card-section>
      </q-card>

      <!-- Insights -->
      <q-card>
        <q-card-section>
          <div class="text-h6 q-mb-md">
            <q-icon name="insights" class="q-mr-sm" />
            Insights & Patterns
          </div>
          <div class="row q-gutter-md">
            <div class="col-12 col-md-6">
              <q-list>
                <q-item v-for="insight in insights" :key="insight.text">
                  <q-item-section avatar>
                    <q-icon :name="insight.icon" :color="insight.color" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>{{ insight.text }}</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </div>
          </div>
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>

<script>
import Chart from 'chart.js/auto'

export default {
  name: 'MoodAnalytics',
  data() {
    return {
      moodData: null,
      loading: false,
      error: null,
      moodChart: null,
      timeChart: null,
    }
  },
  computed: {
    totalEntries() {
      if (!this.moodData) return 0
      return Object.values(this.moodData.counts).reduce((sum, count) => sum + count, 0)
    },
    dominantMood() {
      if (!this.moodData) return { name: '-', count: 0 }
      const counts = this.moodData.counts
      const maxMood = Object.keys(counts).reduce((a, b) => (counts[a] > counts[b] ? a : b))
      return { name: maxMood, count: counts[maxMood] }
    },
    bestTimeSlot() {
      if (!this.moodData) return { name: '-', count: 0 }
      const slots = this.moodData.slots
      let maxSlot = ''
      let maxCount = 0

      Object.keys(slots).forEach((slot) => {
        const count = Object.values(slots[slot]).reduce((sum, c) => sum + c, 0)
        if (count > maxCount) {
          maxCount = count
          maxSlot = slot
        }
      })

      return { name: maxSlot, count: maxCount }
    },
    positiveRatio() {
      if (!this.moodData) return 0
      const counts = this.moodData.counts
      const positiveCount = counts.Happy || 0
      const total = this.totalEntries
      return total > 0 ? Math.round((positiveCount / total) * 100) : 0
    },
    insights() {
      if (!this.moodData) return []

      const insights = []
      const counts = this.moodData.counts
      const slots = this.moodData.slots

      // Dominant mood insight
      insights.push({
        text: `Your most frequent mood was ${this.dominantMood.name} (${this.dominantMood.count} times)`,
        icon: 'trending_up',
        color: 'primary',
      })

      // Time pattern insight
      const morningHappy = slots.morning?.Happy || 0
      const afternoonHappy = slots.afternoon?.Happy || 0
      const nightHappy = slots.night?.Happy || 0

      if (morningHappy >= afternoonHappy && morningHappy >= nightHappy) {
        insights.push({
          text: 'You tend to be happiest in the morning',
          icon: 'wb_sunny',
          color: 'orange',
        })
      } else if (afternoonHappy >= nightHappy) {
        insights.push({
          text: 'Your mood peaks in the afternoon',
          icon: 'light_mode',
          color: 'amber',
        })
      } else {
        insights.push({
          text: 'You find happiness in the evening hours',
          icon: 'nights_stay',
          color: 'indigo',
        })
      }

      // Positive mood ratio insight
      if (this.positiveRatio >= 50) {
        insights.push({
          text: `${this.positiveRatio}% of your moods were positive - great job!`,
          icon: 'sentiment_very_satisfied',
          color: 'positive',
        })
      } else {
        insights.push({
          text: `Consider focusing on activities that boost your mood`,
          icon: 'psychology',
          color: 'info',
        })
      }

      return insights
    },
  },
  async mounted() {
    await this.fetchMoodData()
  },
  beforeUnmount() {
    if (this.moodChart) {
      this.moodChart.destroy()
    }
    if (this.timeChart) {
      this.timeChart.destroy()
    }
  },
  methods: {
    async fetchMoodData() {
      this.loading = true
      this.error = null

      try {
        const endDate = new Date()
        const startDate = new Date()
        startDate.setDate(startDate.getDate() - 6) // Last 7 days including today

        const startStr = startDate.toISOString().split('T')[0]
        const endStr = endDate.toISOString().split('T')[0]

        const { data } = await this.$api.get(
          `/mood-entries/summary?start=${startStr}&end=${endStr}`,
        )
        this.moodData = data

        // Wait for next tick to ensure DOM is updated
        this.$nextTick(() => {
          this.createCharts()
        })
      } catch (error) {
        console.error('Error fetching mood data:', error)
        this.error = 'Failed to load mood data. Please try again.'
      } finally {
        this.loading = false
      }
    },
    createCharts() {
      if (!this.moodData) return

      this.createMoodDistributionChart()
      this.createTimeSlotChart()
    },
    createMoodDistributionChart() {
      const ctx = this.$refs.moodChart.getContext('2d')

      if (this.moodChart) {
        this.moodChart.destroy()
      }

      const counts = this.moodData.counts

      this.moodChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: Object.keys(counts),
          datasets: [
            {
              data: Object.values(counts),
              backgroundColor: [
                '#FFD700', // Happy - Gold
                '#FF6B6B', // Anger - Red
                '#9B59B6', // Fear - Purple
                '#8B4513', // Disgust - Brown
                '#4A90E2', // Sad - Blue
              ],
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
            },
          },
        },
      })
    },
    createTimeSlotChart() {
      const ctx = this.$refs.timeChart.getContext('2d')

      if (this.timeChart) {
        this.timeChart.destroy()
      }

      const slots = this.moodData.slots
      const timeSlots = Object.keys(slots)
      const moods = ['Happy', 'Anger', 'Fear', 'Disgust', 'Sad']

      const datasets = moods.map((mood, index) => ({
        label: mood,
        data: timeSlots.map((slot) => slots[slot][mood] || 0),
        backgroundColor: [
          '#FFD700', // Happy - Gold
          '#FF6B6B', // Anger - Red
          '#9B59B6', // Fear - Purple
          '#8B4513', // Disgust - Brown
          '#4A90E2', // Sad - Blue
        ][index],
      }))

      this.timeChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: timeSlots.map((slot) => slot.charAt(0).toUpperCase() + slot.slice(1)),
          datasets: datasets,
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: {
              stacked: false,
            },
            y: {
              stacked: false,
              beginAtZero: true,
              ticks: {
                stepSize: 1,
              },
            },
          },
          plugins: {
            legend: {
              position: 'bottom',
            },
          },
        },
      })
    },
    formatDate(dateStr) {
      const date = new Date(dateStr)
      return date.toLocaleDateString('en-US', {
        weekday: 'short',
        month: 'short',
        day: 'numeric',
      })
    },
    getMoodColor(mood) {
      const colors = {
        Happy: 'amber',
        Anger: 'red',
        Fear: 'purple',
        Disgust: 'brown',
        Sad: 'blue',
      }
      return colors[mood] || 'grey'
    },
    getMoodIcon(mood) {
      const icons = {
        Happy: 'sentiment_very_satisfied',
        Anger: 'sentiment_very_dissatisfied',
        Fear: 'psychology',
        Disgust: 'sentiment_dissatisfied',
        Sad: 'sentiment_neutral',
      }
      return icons[mood] || 'help'
    },
  },
}
</script>

<style scoped>
.mood-analytics {
  max-width: 1200px;
  margin: 0 auto;
}

.analytics-content {
  animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.timeline-container {
  max-height: 400px;
  overflow-y: auto;
}

.timeline-day {
  padding: 12px;
  border-left: 3px solid #e0e0e0;
  margin-left: 10px;
  position: relative;
}

.timeline-day::before {
  content: '';
  position: absolute;
  left: -6px;
  top: 20px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: #1976d2;
}

.timeline-entry {
  margin: 4px 0;
}

canvas {
  max-height: 300px;
}
</style>
