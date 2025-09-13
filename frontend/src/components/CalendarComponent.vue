<template>
  <div class="flex column">
    <div class="row items-center justify-start q-pa-none">
      <div class="month-header q-ma-sm">
        <div class="text-h6 text-bold">{{ currentMonthName }} {{ currentYear }}</div>
      </div>
      <q-btn round icon="chevron_left" @click="changeMonth(-1)" class="q-mr-md cal-btn" />

      <q-btn round icon="chevron_right" @click="changeMonth(1)" class="q-mr-sm cal-btn" />
    </div>
    <MonthlyView :monthlyData="processedMonthlyData" />
  </div>
</template>

<script>
import MonthlyView from 'src/components/MonthlyView.vue'

export default {
  name: 'CalendarPage',
  components: { MonthlyView },

  data() {
    const now = new Date()
    return {
      currentYear: now.getFullYear(),
      currentMonth: now.getMonth(), // 0-based
      moodData: {},
    }
  },

  computed: {
    processedMonthlyData() {
      const days = Object.entries(this.moodData).map(([dateString, dayMoods]) => ({
        day: new Date(dateString).getDate(),
        moods: [...new Set(Object.values(dayMoods))],
      }))

      return {
        year: this.currentYear,
        month: this.currentMonth,
        days,
      }
    },

    currentMonthName() {
      return new Date(this.currentYear, this.currentMonth).toLocaleString('deadult', {
        month: 'long',
      })
    },
  },

  watch: {
    currentMonth: 'fetchMonthData',
    currentYear: 'fetchMonthData',
  },

  mounted() {
    this.fetchMonthData()
  },

  methods: {
    changeMonth(delta) {
      let newMonth = this.currentMonth + delta
      let newYear = this.currentYear

      if (newMonth < 0) {
        newMonth = 11
        newYear--
      } else if (newMonth > 11) {
        newMonth = 0
        newYear++
      }

      this.currentMonth = newMonth
      this.currentYear = newYear
    },

    async fetchMonthData() {
      const start = new Date(this.currentYear, this.currentMonth, 1)
      const end = new Date(this.currentYear, this.currentMonth + 1, 0)

      const startStr = start.toISOString().split('T')[0]
      const endStr = end.toISOString().split('T')[0]

      try {
        const { data } = await this.$api.get(
          `/mood-entries/range-primary?start=${startStr}&end=${endStr}`,
        )
        this.moodData = data
      } catch (err) {
        console.error('Failed to fetch mood data:', err)
        this.moodData = {}
      }
    },
  },
}
</script>

<style>
.month-header {
  /* margin: 0; */
  /* font-weight: 500; */
}

.cal-btn {
  color: white;
  background-color: #6b11e3;

  width: 18px;
  height: 18px;
  font-size: 10px; /* icon size */
  padding: 0;
}
</style>
