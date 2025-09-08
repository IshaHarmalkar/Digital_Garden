<template>
  <div class="q-gutter-md row wrap">
    <DayCard
      v-for="dayObj in monthDays"
      :key="dayObj.date"
      :day="new Date(dayObj.date).getDate()"
      :moods="dayObj.moods"
    />
  </div>
</template>

<script>
import DayCard from './DayCard.vue'

export default {
  name: 'MonthlyView',
  components: { DayCard },

  computed: {
    monthDays() {
      const dates = []
      const monthData = this.month
      const firstDate = new Date(Object.keys(this.month)[0])

      const year = firstDate.getFullYear()
      const month = firstDate.getMonth()

      //get number of days in month
      const daysInMonth = new Date(year, month + 1, 0).getDate()

      for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`
        dates.push({
          date: dateStr,
          moods: monthData[dateStr] ? Object.values(monthData[dateStr]) : [],
        })
      }

      return dates
    },
  },

  data() {
    return {
      month: {
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
      },
    }
  },
}
</script>

<style lang="scss" scoped></style>
