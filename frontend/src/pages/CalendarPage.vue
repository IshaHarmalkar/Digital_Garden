<template>
  <div class="flex flex-center column">
    <div class="row items-center q-ma-md">
      <q-btn label="Previous" @click="changeMonth(-1)" class="q-mr-md" />
      <!-- <div class="q-pa-md text-white">Filler Text</div>
      <div>{{ currentYear }} - {{ currentMonth + 1 }}</div> -->

      <q-btn label="Next" @click="changeMonth(1)" lass="q-mr-md" />
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
.month-header h4 {
  margin: 0;
  font-weight: 500;
}
</style>
