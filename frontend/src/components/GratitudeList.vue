<template>
  <div class="">
    <div>
      <q-btn
        unelevated
        label="Gratitude List"
        color="primary"
        class="full-width q-mb-md primary-rounded-btn q-pa-md"
        @click="gratitudeDialog = true"
      />
    </div>
    <div class="list-content">
      <q-list bordered separator class="scroll-list">
        <div class="scroll-wrapper">
          <q-item v-for="(gratitude, day) in gratitudes" :key="day" clickable v-ripple>
            <q-item-section avatar>
              <q-icon color="primary" name="album" />
            </q-item-section>
            <q-item-section>{{ gratitude }}</q-item-section>
          </q-item>

          <!-- duplicate for looping -->
          <q-item v-for="(gratitude, day) in gratitudes" :key="day" clickable v-ripple>
            <q-item-section avatar>
              <q-icon color="primary" name="album" />
            </q-item-section>
            <q-item-section>{{ gratitude }}</q-item-section>
          </q-item>
        </div>
      </q-list>
    </div>

    <!-- pop up reflect form -->
    <q-dialog v-model="gratitudeDialog" persistent>
      <reflect-form />
    </q-dialog>
  </div>
</template>

<script>
import ReflectForm from './ReflectForm.vue'
export default {
  name: 'GratitudeList',
  components: { ReflectForm },

  data() {
    return {
      gratitudes: {},
      gratitudeDialog: false,
      gratitudeInput: '',
    }
  },

  methods: {
    async fetchGratitudes() {
      const { data } = await this.$api.get('/reflections/gratitudes')
      this.gratitudes = data
    },
  },

  mounted() {
    this.fetchGratitudes()
  },
}
</script>

<style lang="scss" scoped>
.border {
  border: solid 1px black;
}

.list-content {
  flex: 1;
  overflow: hidden;
  position: relative;
}

.scroll-wrapper {
  display: flex;
  flex-direction: column;
  animation: scrollUp linear 10s infinite;
  height: 400px;
}

.primary-rounded-btn {
  border-radius: 15px;
}

@keyframes scrollUp {
  0% {
    transform: translateY(0);
  }

  100% {
    transform: translateY(-50%);
  }
}
</style>
