<template>
  <q-page class="">
    <div class="row q-ma-md q-pa-md">
      <div class="text-h3 q-ml-md text-deep-purple-3">
        <span>Hi! Isha</span> <span>|</span> <span>{{ todayFormatted }}</span>
      </div>
      <!-- <div class="text-h6 plain-font">
        Now that you don't have to be <span class="fancy-cursive text-h2"> perfect,</span> You can
        be <span class="fancy-cursive text-h2"> good</span>
      </div> -->
    </div>
    <div class="row" style="height: 80%">
      <div class="col-8 q-ma-md q-ml-xl" style="height: 100%">
        <calendar-component style="height: 100%" />
      </div>
      <div class="col q-ma-md">
        <div>
          <gratitude-list />
        </div>

        <div>
          <!-- <div><q-btn label="Gratitude List" color="primary" class="full-width" /></div> -->
        </div>
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

  computed: {
    todayFormatted() {
      const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }
      return new Date().toLocaleDateString(undefined, options)
    },
  },
}
</script>

<style scoped></style>
