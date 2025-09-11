<template>
  <q-page class="">
    <div class="row q-ma-md q-pa-md">
      <div class="text-h6 plain-font">
        Now that you don't have to be <span class="fancy-cursive text-h2"> perfect,</span> You can
        be <span class="fancy-cursive text-h2"> good</span>
      </div>
    </div>
    <div class="row" style="height: 100%">
      <div class="col-8 q-ma-md" style="display: flex; flex-direction: column">
        <calendar-component style="flex: 1" />
      </div>
      <div class="col q-ma-md" style="display: flex; flex-direction: column">
        <gratitude-list style="flex: 1" />
      </div>
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
