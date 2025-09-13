<template>
  <div class="flex flex-center column">
    <calendar-component />
  </div>
</template>

<script>
import CalendarComponent from 'src/components/CalendarComponent.vue'
export default {
  name: 'CalendarPage',
  components: { CalendarComponent },
}
</script>

<style>
.month-header h4 {
  margin: 0;
  font-weight: 500;
}
</style>
