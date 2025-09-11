<template>
  <div class="flex flex-center">
    <MonthlyView :monthlyData="processedMonthlyData" />
  </div>
</template>

<script>
import MonthlyView from 'src/components/MonthlyView.vue'

export default {
  name: 'CalendarPage',
  components: {
    MonthlyView,
  },

  data() {
    return {
      moodData: {},
    }
  },

  computed: {
    processedMonthlyData() {
      // Extract year and month from the first date in the data
      const firstDate = Object.keys(this.moodData)[0]
      if (!firstDate) {
        return {
          year: new Date().getFullYear(),
          month: new Date().getMonth(),
          days: [],
        }
      }

      const date = new Date(firstDate)
      const year = date.getFullYear()
      const month = date.getMonth() // 0-based month

      // Process the mood data into the format expected by MonthlyView
      const days = Object.entries(this.moodData).map(([dateString, dayMoods]) => {
        const dayDate = new Date(dateString)
        const dayNumber = dayDate.getDate()

        // Collect all unique moods for the day
        const allMoods = Object.values(dayMoods)
        const uniqueMoods = [...new Set(allMoods)]

        return {
          day: dayNumber,
          moods: uniqueMoods,
        }
      })

      return {
        year,
        month,
        days,
      }
    },
  },

  async mounted() {
    try {
      const start = '2025-08-01'
      const end = '2025-08-31'

      const { data } = await this.$api.get(`/mood-entries/range-primary?start=${start}&end=${end}`)
      this.moodData = data
    } catch (err) {
      console.error('Failed to fetch mood data:', err)
    }
  },
}
</script>

<style lang="scss" scoped>
.test-class {
  border: solid 1px red;
}
</style>
