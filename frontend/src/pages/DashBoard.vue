<template>
  <q-page class="">
    <div class="row q-ma-md q-pa-md">
      <div class="text-h6 plain-font">
        Now that you don't have to be <span class="fancy-cursive text-h2"> perfect,</span> You can
        be <span class="fancy-cursive text-h2"> good</span>
      </div>
    </div>
    <div class="row">
      <div class="col-8 q-ma-md">
        <div><calendar-component /></div>
      </div>
      <div class="col q-ma-md"><gratitude-list /></div>
    </div>
  </q-page>
</template>

<script>
import CalendarComponent from 'src/components/CalendarComponent.vue'
import GratitudeList from 'src/components/GratitudeList.vue'

export default {
  components: {
    GratitudeList,
    CalendarComponent,
  },
}
</script>

<style scoped></style>
