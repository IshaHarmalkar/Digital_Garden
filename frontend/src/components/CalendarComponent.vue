<template>
  <div class="calendar-container">
    <!-- Calendar Header -->
    <div class="calendar-header q-pa-md">
      <q-btn flat round icon="chevron_left" @click="previousMonth" color="primary" />
      <div class="text-h5 text-weight-medium">
        {{ currentMonthYear }}
      </div>
      <q-btn flat round icon="chevron_right" @click="nextMonth" color="primary" />
    </div>

    <!-- Days of Week Header -->
    <div class="days-header row">
      <div
        v-for="day in daysOfWeek"
        :key="day"
        class="col text-center q-pa-sm text-weight-medium text-grey-7"
      >
        {{ day }}
      </div>
    </div>

    <!-- Calendar Grid -->
    <div class="calendar-grid">
      <div v-for="week in calendarWeeks" :key="week[0]?.date || 'empty'" class="row">
        <div v-for="day in week" :key="day?.date || Math.random()" class="col calendar-cell">
          <DayCard
            v-if="day"
            :date="day.date"
            :day-number="day.dayNumber"
            :is-current-month="day.isCurrentMonth"
            :is-today="day.isToday"
            :events="day.events"
            :tasks="day.tasks"
            :notes="day.notes"
            @click="onDayClick"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'

import DayCard from './DayCard.vue'

export default {
  name: 'CalendarComponent',
  components: {
    DayCard,
  },
  setup() {
    const currentDate = ref(new Date())
    const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']

    // Sample data - in real app this would come from props or API
    const sampleData = ref({
      '2024-01-15': {
        events: ['Meeting', 'Lunch'],
        tasks: ['Review code', 'Update docs'],
        notes: ['Important call'],
      },
      '2024-01-20': {
        events: ['Conference'],
        tasks: ['Prepare presentation'],
        notes: [],
      },
      '2024-01-25': {
        events: ['Team sync'],
        tasks: ['Code review', 'Testing'],
        notes: ['Follow up needed'],
      },
    })

    const currentMonthYear = computed(() => {
      return currentDate.value.toLocaleDateString('en-US', {
        month: 'long',
        year: 'numeric',
      })
    })

    const calendarWeeks = computed(() => {
      const year = currentDate.value.getFullYear()
      const month = currentDate.value.getMonth()

      // First day of the month
      const firstDay = new Date(year, month, 1)
      const lastDay = new Date(year, month + 1, 0)

      // Start from the beginning of the week containing the first day
      const startDate = new Date(firstDay)
      startDate.setDate(startDate.getDate() - firstDay.getDay())

      const weeks = []
      let currentWeek = []
      let date = new Date(startDate)

      // Generate 6 weeks to ensure full calendar view
      for (let i = 0; i < 42; i++) {
        const dayData = {
          date: new Date(date),
          dayNumber: date.getDate(),
          isCurrentMonth: date.getMonth() === month,
          isToday: isToday(date),
          events: [],
          tasks: [],
          notes: [],
        }

        // Add sample data if exists
        const dateKey = formatDateKey(date)
        if (sampleData.value[dateKey]) {
          dayData.events = sampleData.value[dateKey].events || []
          dayData.tasks = sampleData.value[dateKey].tasks || []
          dayData.notes = sampleData.value[dateKey].notes || []
          console.log(lastDay)
        }

        currentWeek.push(dayData)

        if (currentWeek.length === 7) {
          weeks.push(currentWeek)
          currentWeek = []
        }

        date.setDate(date.getDate() + 1)
      }

      return weeks
    })

    const isToday = (date) => {
      const today = new Date()
      return date.toDateString() === today.toDateString()
    }

    const formatDateKey = (date) => {
      return date.toISOString().split('T')[0]
    }

    const previousMonth = () => {
      currentDate.value = new Date(
        currentDate.value.getFullYear(),
        currentDate.value.getMonth() - 1,
        1,
      )
    }

    const nextMonth = () => {
      currentDate.value = new Date(
        currentDate.value.getFullYear(),
        currentDate.value.getMonth() + 1,
        1,
      )
    }

    const onDayClick = (dayData) => {
      console.log('Day clicked:', dayData)
      // Handle day click - could emit event or open modal
    }

    onMounted(() => {
      // Initialize with current date
      currentDate.value = new Date()
    })

    return {
      currentDate,
      daysOfWeek,
      currentMonthYear,
      calendarWeeks,
      previousMonth,
      nextMonth,
      onDayClick,
    }
  },
}
</script>

<style scoped>
.calendar-container {
  max-width: 100%;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e0e0e0;
}

.days-header {
  border-bottom: 1px solid #e0e0e0;
  background: #f5f5f5;
}

.calendar-grid {
  min-height: 600px;
}

.calendar-cell {
  min-height: 100px;
  border-right: 1px solid #e0e0e0;
  border-bottom: 1px solid #e0e0e0;
}

.calendar-cell:last-child {
  border-right: none;
}
</style>
