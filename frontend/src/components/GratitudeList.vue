<template>
  <div class="">
    <div><q-btn label="Gratitude List" color="primary" class="full-width" /></div>
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
  </div>
</template>

<script>
export default {
  name: 'GratitudeList',

  data() {
    return {
      gratitudes: {},
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
  height: 100%;
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
