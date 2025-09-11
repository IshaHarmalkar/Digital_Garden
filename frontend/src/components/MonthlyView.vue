<template>
  <div class="monthly-view">
    <!-- Month Header -->
    <!-- <div class="month-header q-pa-md">
      <h4 class="text-center">{{ monthName }}</h4>
    </div> -->

    <!-- Days of Week Header -->
    <div class="days-header row">
      <div
        v-for="day in daysOfWeek"
        :key="day"
        class="day-header col text-center text-weight-medium text-grey-7 q-pa-sm"
      >
        {{ day }}
      </div>
    </div>

    <!-- Calendar Grid -->
    <div class="calendar-grid">
      <div v-for="week in weeks" :key="week.weekIndex" class="week-row row">
        <div v-for="day in week.days" :key="day.key" class="day-container col">
          <DayCard :day="day.dayNumber" :moods="day.moods" :blank="day.isBlank" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DayCard from './DayCard.vue'

export default {
  name: 'MonthlyView',
  components: {
    DayCard,
  },

  props: {
    monthlyData: {
      type: Object,
      default: () => ({
        year: new Date().getFullYear(),
        month: new Date().getMonth(), // 0-based (0 = January)
        days: [], // Array of objects with day number and moods
      }),
    },
  },

  data() {
    return {
      daysOfWeek: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    }
  },

  computed: {
    monthName() {
      const date = new Date(this.monthlyData.year, this.monthlyData.month)
      return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
    },

    firstDayOfMonth() {
      return new Date(this.monthlyData.year, this.monthlyData.month, 1)
    },

    lastDayOfMonth() {
      return new Date(this.monthlyData.year, this.monthlyData.month + 1, 0)
    },

    // Get the day of week for first day (0=Sunday, 1=Monday, etc.)
    // Convert to Monday=0, Tuesday=1, etc.
    firstDayWeekday() {
      const day = this.firstDayOfMonth.getDay()
      return day === 0 ? 6 : day - 1 // Convert Sunday (0) to 6, Monday (1) to 0, etc.
    },

    daysInMonth() {
      return this.lastDayOfMonth.getDate()
    },

    weeks() {
      const weeks = []
      let currentWeek = []
      let dayCounter = 1

      // First week - add blank days before month starts
      for (let i = 0; i < this.firstDayWeekday; i++) {
        currentWeek.push({
          key: `blank-${i}`,
          dayNumber: null,
          moods: [],
          isBlank: true,
        })
      }

      // Add days of the month
      while (dayCounter <= this.daysInMonth) {
        // Find data for current day
        const dayData = this.monthlyData.days.find((d) => d.day === dayCounter) || {}

        currentWeek.push({
          key: `day-${dayCounter}`,
          dayNumber: dayCounter,
          moods: dayData.moods || [],
          isBlank: false,
        })

        // If we've filled a week (7 days), start a new week
        if (currentWeek.length === 7) {
          weeks.push({
            weekIndex: weeks.length,
            days: [...currentWeek],
          })
          currentWeek = []
        }

        dayCounter++
      }

      // Fill remaining days in last week with blanks
      while (currentWeek.length > 0 && currentWeek.length < 7) {
        currentWeek.push({
          key: `blank-end-${currentWeek.length}`,
          dayNumber: null,
          moods: [],
          isBlank: true,
        })
      }

      // Add the last week if it has any days
      if (currentWeek.length > 0) {
        weeks.push({
          weekIndex: weeks.length,
          days: currentWeek,
        })
      }

      return weeks
    },
  },
}
</script>

<style lang="scss" scoped>
.monthly-view {
  width: 100%;
  max-width: 1000px;
  margin: 0 auto;
}

.month-header h4 {
  margin: 0;
  font-weight: 500;
}

.days-header {
  // padding: 0 4px;
  background-color: #eff3ff;
}

.day-header {
  font-size: 0.9rem;
  //padding: 8px 0;
  border: solid 1px #885df1;
  margin: 2px 2px;
}

.calendar-grid {
  width: 100%;
}

.week-row {
  width: 100%;
}

.day-container {
  flex: 1;
  min-width: 0; /* Important for flex items to shrink properly */
  margin: 2px 2px;
}

.border {
  border: solid 1px grey;
}

.test-2 {
  border: 1px solid red;
  background-color: #885df1;
}
</style>
