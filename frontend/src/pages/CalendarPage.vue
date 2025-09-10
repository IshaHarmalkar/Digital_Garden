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
      moodData: {
        '2025-08-01': {
          morning: 'Surprise',
          afternoon: 'Surprise',
          night: 'Fear',
        },
        '2025-08-02': {
          morning: 'Sad',
          afternoon: 'Sad',
          night: 'Anger',
        },
        '2025-08-03': {
          morning: 'Surprise',
          afternoon: 'Sad',
          night: 'Sad',
        },
        '2025-08-04': {
          morning: 'Happy',
          afternoon: 'Sad',
          night: 'Disgust',
        },
        '2025-08-05': {
          morning: 'Happy',
          afternoon: 'Sad',
          night: 'Happy',
        },
        '2025-08-06': {
          morning: 'Anger',
          afternoon: 'Disgust',
          night: 'Sad',
        },
        '2025-08-07': {
          morning: 'Sad',
          afternoon: 'Disgust',
          night: 'Sad',
        },
        '2025-08-08': {
          morning: 'Fear',
          afternoon: 'Happy',
          night: 'Anger',
        },
        '2025-08-09': {
          morning: 'Happy',
          afternoon: 'Happy',
          night: 'Sad',
        },
        '2025-08-10': {
          morning: 'Anger',
          afternoon: 'Fear',
          night: 'Sad',
        },
        '2025-08-11': {
          morning: 'Sad',
          afternoon: 'Sad',
          night: 'Sad',
        },
        '2025-08-12': {
          morning: 'Surprise',
          afternoon: 'Anger',
          night: 'Sad',
        },
        '2025-08-13': {
          morning: 'Fear',
          afternoon: 'Anger',
          night: 'Disgust',
        },
        '2025-08-14': {
          morning: 'Happy',
          afternoon: 'Fear',
          night: 'Sad',
        },
        '2025-08-15': {
          morning: 'Disgust',
          afternoon: 'Sad',
          night: 'Happy',
        },
        '2025-08-16': {
          morning: 'Surprise',
          afternoon: 'Fear',
          night: 'Happy',
        },
        '2025-08-17': {
          morning: 'Happy',
          afternoon: 'Surprise',
          night: 'Fear',
        },
        '2025-08-18': {
          morning: 'Fear',
          afternoon: 'Surprise',
          night: 'Sad',
        },
        '2025-08-19': {
          morning: 'Happy',
          afternoon: 'Fear',
          night: 'Anger',
        },
        '2025-08-20': {
          morning: 'Happy',
          afternoon: 'Sad',
          night: 'Sad',
        },
        '2025-08-21': {
          morning: 'Sad',
          afternoon: 'Sad',
          night: 'Anger',
        },
        '2025-08-22': {
          morning: 'Sad',
          afternoon: 'Sad',
          night: 'Surprise',
        },
        '2025-08-23': {
          morning: 'Anger',
          afternoon: 'Happy',
          night: 'Happy',
        },
        '2025-08-24': {
          morning: 'Happy',
          afternoon: 'Surprise',
          night: 'Disgust',
        },
        '2025-08-25': {
          morning: 'Fear',
          afternoon: 'Happy',
          night: 'Fear',
        },
        '2025-08-26': {
          morning: 'Sad',
          afternoon: 'Disgust',
          night: 'Happy',
        },
        '2025-08-27': {
          morning: 'Surprise',
          afternoon: 'Anger',
          night: 'Anger',
        },
        '2025-08-28': {
          morning: 'Surprise',
          afternoon: 'Fear',
          night: 'Fear',
        },
        '2025-08-29': {
          morning: 'Fear',
          afternoon: 'Surprise',
          night: 'Happy',
        },
        '2025-08-30': {
          morning: 'Sad',
          afternoon: 'Fear',
          night: 'Surprise',
        },
        '2025-08-31': {
          morning: 'Disgust',
          afternoon: 'Anger',
          night: 'Happy',
        },
      },
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
}
</script>

<style lang="scss" scoped>
.test-class {
  border: solid 1px red;
}
</style>
