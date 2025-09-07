<template>
  <q-page class="calendar-page">
    <div class="q-pa-md">
      <h4 class="text-weight-light q-mb-md">My Calendar</h4>

      <!-- Calendar Component -->
      <CalendarComponent :calendar-data="calendarData" @day-selected="onDaySelected" />

      <!-- Day Detail Modal -->
      <q-dialog v-model="showDayDetail">
        <q-card class="day-detail-card">
          <q-card-section>
            <div class="text-h6">
              {{
                selectedDay?.date?.toLocaleDateString('en-US', {
                  weekday: 'long',
                  year: 'numeric',
                  month: 'long',
                  day: 'numeric',
                })
              }}
            </div>
          </q-card-section>

          <q-card-section v-if="selectedDay">
            <!-- Events Section -->
            <div v-if="selectedDay.events?.length" class="q-mb-md">
              <div class="text-subtitle2 q-mb-sm">Events</div>
              <q-chip
                v-for="event in selectedDay.events"
                :key="event"
                color="primary"
                text-color="white"
                :label="event"
                class="q-mr-xs q-mb-xs"
              />
            </div>

            <!-- Tasks Section -->
            <div v-if="selectedDay.tasks?.length" class="q-mb-md">
              <div class="text-subtitle2 q-mb-sm">Tasks</div>
              <q-chip
                v-for="task in selectedDay.tasks"
                :key="task"
                color="orange"
                text-color="white"
                :label="task"
                icon="task_alt"
                class="q-mr-xs q-mb-xs"
              />
            </div>

            <!-- Notes Section -->
            <div v-if="selectedDay.notes?.length">
              <div class="text-subtitle2 q-mb-sm">Notes</div>
              <q-chip
                v-for="note in selectedDay.notes"
                :key="note"
                color="green"
                text-color="white"
                :label="note"
                icon="sticky_note_2"
                class="q-mr-xs q-mb-xs"
              />
            </div>

            <!-- Empty State -->
            <div
              v-if="
                !selectedDay.events?.length &&
                !selectedDay.tasks?.length &&
                !selectedDay.notes?.length
              "
            >
              <div class="text-grey-6 text-center q-pa-md">
                No events, tasks, or notes for this day
              </div>
            </div>
          </q-card-section>

          <q-card-actions align="right">
            <q-btn flat label="Close" color="primary" @click="showDayDetail = false" />
            <q-btn label="Add Event" color="primary" @click="addEvent" />
          </q-card-actions>
        </q-card>
      </q-dialog>
    </div>
  </q-page>
</template>

<script>
import { ref } from 'vue'

export default {
  name: 'CalendarExample',
  setup() {
    const showDayDetail = ref(false)
    const selectedDay = ref(null)

    // Sample calendar data - in a real app this would come from an API
    const calendarData = ref({
      '2024-01-15': {
        events: ['Team Meeting', 'Client Call', 'Lunch with Sarah'],
        tasks: ['Review PR #123', 'Update documentation', 'Send weekly report'],
        notes: ['Remember to follow up on proposal', 'Check server logs'],
      },
      '2024-01-16': {
        events: ['Dentist Appointment'],
        tasks: ['Code review', 'Deploy to staging'],
        notes: ['Call mom'],
      },
      '2024-01-20': {
        events: ['Vue.js Conference', 'Networking Event'],
        tasks: ['Prepare presentation slides'],
        notes: ['Bring business cards', 'Arrive 30 min early'],
      },
      '2024-01-22': {
        events: ['Project Kickoff'],
        tasks: ['Setup development environment', 'Create project structure', 'Write initial tests'],
        notes: ['Discuss timeline with stakeholders'],
      },
      '2024-01-25': {
        events: ['Team Retrospective', 'Sprint Planning'],
        tasks: ['Update Jira tickets', 'Review sprint goals'],
        notes: ['Prepare feedback for retro'],
      },
      '2024-01-28': {
        events: ['Birthday Party'],
        tasks: [],
        notes: ['Buy gift', 'Confirm attendance'],
      },
    })

    const onDaySelected = (dayData) => {
      selectedDay.value = dayData
      showDayDetail.value = true
    }

    const addEvent = () => {
      // In a real app, this would open a form to add new events
      console.log('Add event for:', selectedDay.value?.date)
      // You could open another modal or navigate to an event creation page
    }

    return {
      calendarData,
      showDayDetail,
      selectedDay,
      onDaySelected,
      addEvent,
    }
  },
}
</script>

<style scoped>
.calendar-page {
  background: #fafafa;
  min-height: 100vh;
}

.day-detail-card {
  min-width: 400px;
  max-width: 600px;
}

@media (max-width: 768px) {
  .day-detail-card {
    min-width: 90vw;
  }
}
</style>
