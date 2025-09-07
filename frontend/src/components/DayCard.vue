<template>
  <div :class="dayCardClasses" @click="handleClick" class="day-card cursor-pointer">
    <!-- Day Number -->
    <div class="day-number" :class="dayNumberClasses">
      {{ dayNumber }}
    </div>

    <!-- Content Area -->
    <div class="day-content">
      <!-- Events -->
      <div v-if="events.length" class="chips-section">
        <q-chip
          v-for="(event, index) in displayEvents"
          :key="`event-${index}`"
          size="xs"
          color="primary"
          text-color="white"
          :label="event"
          class="chip-item"
        />
        <q-chip
          v-if="events.length > maxDisplayItems"
          size="xs"
          color="primary"
          outline
          :label="`+${events.length - maxDisplayItems} more`"
          class="chip-item"
        />
      </div>

      <!-- Tasks -->
      <div v-if="tasks.length" class="chips-section">
        <q-chip
          v-for="(task, index) in displayTasks"
          :key="`task-${index}`"
          size="xs"
          color="orange"
          text-color="white"
          :label="task"
          class="chip-item"
          icon="task_alt"
        />
        <q-chip
          v-if="tasks.length > maxDisplayItems"
          size="xs"
          color="orange"
          outline
          :label="`+${tasks.length - maxDisplayItems} tasks`"
          class="chip-item"
        />
      </div>

      <!-- Notes -->
      <div v-if="notes.length" class="chips-section">
        <q-chip
          v-for="(note, index) in displayNotes"
          :key="`note-${index}`"
          size="xs"
          color="green"
          text-color="white"
          :label="note"
          class="chip-item"
          icon="sticky_note_2"
        />
        <q-chip
          v-if="notes.length > maxDisplayItems"
          size="xs"
          color="green"
          outline
          :label="`+${notes.length - maxDisplayItems} notes`"
          class="chip-item"
        />
      </div>
    </div>

    <!-- Activity Indicator -->
    <div v-if="hasContent" class="activity-indicator" :class="activityIndicatorClass"></div>
  </div>
</template>

<script>
import { computed } from 'vue'

export default {
  name: 'DayCard',
  props: {
    date: {
      type: Date,
      required: true,
    },
    dayNumber: {
      type: Number,
      required: true,
    },
    isCurrentMonth: {
      type: Boolean,
      default: true,
    },
    isToday: {
      type: Boolean,
      default: false,
    },
    events: {
      type: Array,
      default: () => [],
    },
    tasks: {
      type: Array,
      default: () => [],
    },
    notes: {
      type: Array,
      default: () => [],
    },
    maxDisplayItems: {
      type: Number,
      default: 2,
    },
  },
  emits: ['click'],
  setup(props, { emit }) {
    const dayCardClasses = computed(() => ({
      'day-card': true,
      'current-month': props.isCurrentMonth,
      'other-month': !props.isCurrentMonth,
      today: props.isToday,
      'has-content': hasContent.value,
    }))

    const dayNumberClasses = computed(() => ({
      'today-number': props.isToday,
      'other-month-number': !props.isCurrentMonth,
    }))

    const hasContent = computed(() => {
      return props.events.length > 0 || props.tasks.length > 0 || props.notes.length > 0
    })

    const activityIndicatorClass = computed(() => {
      const totalItems = props.events.length + props.tasks.length + props.notes.length
      if (totalItems >= 5) return 'high-activity'
      if (totalItems >= 3) return 'medium-activity'
      return 'low-activity'
    })

    const displayEvents = computed(() => {
      return props.events.slice(0, props.maxDisplayItems)
    })

    const displayTasks = computed(() => {
      return props.tasks.slice(0, props.maxDisplayItems)
    })

    const displayNotes = computed(() => {
      return props.notes.slice(0, props.maxDisplayItems)
    })

    const handleClick = () => {
      emit('click', {
        date: props.date,
        dayNumber: props.dayNumber,
        events: props.events,
        tasks: props.tasks,
        notes: props.notes,
        isToday: props.isToday,
        isCurrentMonth: props.isCurrentMonth,
      })
    }

    return {
      dayCardClasses,
      dayNumberClasses,
      hasContent,
      activityIndicatorClass,
      displayEvents,
      displayTasks,
      displayNotes,
      handleClick,
    }
  },
}
</script>

<style scoped>
.day-card {
  width: 100%;
  height: 100%;
  min-height: 100px;
  padding: 8px;
  position: relative;
  transition: all 0.2s ease;
  display: flex;
  flex-direction: column;
}

.day-card:hover {
  background-color: #f5f5f5;
  transform: translateY(-1px);
}

.day-card.today {
  background-color: #e3f2fd;
  border-left: 4px solid #2196f3;
}

.day-card.other-month {
  opacity: 0.4;
}

.day-number {
  font-weight: 600;
  font-size: 14px;
  color: #333;
  margin-bottom: 4px;
  flex-shrink: 0;
}

.day-number.today-number {
  color: #2196f3;
  font-weight: 700;
}

.day-number.other-month-number {
  color: #999;
}

.day-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 4px;
  overflow: hidden;
}

.chips-section {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.chip-item {
  margin: 0 !important;
  font-size: 10px !important;
  max-width: 100%;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.activity-indicator {
  position: absolute;
  top: 4px;
  right: 4px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
}

.low-activity {
  background-color: #4caf50;
}

.medium-activity {
  background-color: #ff9800;
}

.high-activity {
  background-color: #f44336;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .day-card {
    min-height: 80px;
    padding: 4px;
  }

  .day-number {
    font-size: 12px;
  }

  .chip-item {
    font-size: 9px !important;
  }
}
</style>
