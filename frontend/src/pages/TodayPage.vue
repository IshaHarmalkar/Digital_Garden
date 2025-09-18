<template>
  <q-page class="q-pa-md">
    <!-- Buttons to open dialogs -->

    <div class="main-container">
      <div class="row q-ma-md">
        <!-- <span class="text-h3 plain-font text-primary">Sunday | 14 September 2025</span> -->
        <span class="text-h3 plain-font text-primary">{{ todayFormatted }}</span>
      </div>
      <div class="row">
        <div class="col q-ma-md">
          <!-- Analytics -->
          <mood-analytics />
          <div class="q-mt-md">
            <q-btn
              unelevated
              label="Mood"
              color="secondary"
              icon-right="add_reaction"
              @click="moodDialog = true"
              class="full-width q-pa-md primary-rounded-btn"
            />
          </div>
        </div>

        <div class="col q-ma-md">
          <!-- gratitude -->
          <gratitude-list />
        </div>
      </div>
    </div>

    <!-- Mood Form Dialog -->
    <q-dialog v-model="moodDialog" persistent>
      <q-card class="q-pa-md" style="min-width: 400px">
        <q-card-section>
          <div class="text-h6">Mood Form</div>
        </q-card-section>
        <q-separator />
        <q-card-section>
          <mood-form />
        </q-card-section>
        <q-card-actions align="right">
          <!-- Close button -->
          <q-btn flat label="Close" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <!-- Reflect Form Dialog -->
    <q-dialog v-model="reflectDialog" persistent>
      <q-card class="q-pa-md" style="min-width: 400px">
        <q-card-section>
          <div class="text-h6">Reflect Form</div>
        </q-card-section>
        <q-separator />
        <q-card-section>
          <reflect-form />
        </q-card-section>
        <q-card-actions align="right">
          <!-- Close button -->
          <q-btn flat label="Close" color="primary" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script>
import MoodForm from 'src/components/MoodForm.vue'
import ReflectForm from 'src/components/ReflectForm.vue'
import MoodAnalytics from 'src/components/MoodAnalytics.vue'
import GratitudeList from 'src/components/GratitudeList.vue'

export default {
  components: {
    MoodForm,
    ReflectForm,
    MoodAnalytics,
    GratitudeList,
  },
  data() {
    return {
      moodDialog: false,
      reflectDialog: false,
    }
  },
  computed: {
    todayFormatted() {
      const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }
      return new Date().toLocaleDateString(undefined, options)
    },
  },
}
</script>

<style scoped>
.primary-rounded-btn {
  border-radius: 15px;
}
</style>
