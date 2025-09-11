<template>
  <div class="">
    <q-card square class="day-card flex q-pa-sm" :style="{ backgroundColor: 'white' }">
      <div class="day-content column">
        <template v-if="!blank">
          <div class="row justify-end text-grey">{{ formattedDay }}</div>

          <div class="row justify-items">
            <q-chip
              square
              dense
              v-for="(mood, index) in moods"
              :key="index"
              class="mood-chip"
              :style="{
                backgroundColor: moodColors[mood],
              }"
            >
              {{ mood }}
            </q-chip>
          </div>
        </template>
      </div>
    </q-card>
  </div>
</template>

<script>
export default {
  name: 'DayCard',
  props: {
    day: {
      type: [Number, String],
      required: true,
    },

    moods: {
      type: Array,
      default: () => [],
    },

    blank: {
      type: Boolean,
      default: false,
    },
  },

  computed: {
    formattedDay() {
      //blank day
      if (!this.day) return ''

      if (this.day < 10) {
        return '0' + this.day
      }
      return String(this.day)
    },

    moodColors() {
      return {
        Happy: '#f054a5',
        Surprise: '#cbd538',
        Anger: '#ff9b29',
        Fear: '#7b88fa',
        Sad: '#bfe0f3',
        Disgust: '#fabf37',
      }
    },
  },

  methods: {
    darkenHex(hex, amount = 30) {
      let c = hex.replace('#', '')
      if (c.length === 3)
        c = c
          .split('')
          .map((x) => x + x)
          .join('')
      let num = parseInt(c, 16)
      let r = Math.max((num >> 16) - amount, 0)
      let g = Math.max(((num >> 8) & 0x00ff) - amount, 0)
      let b = Math.max((num & 0x0000ff) - amount, 0)
      return `rgb(${r},${g},${b})`
    },
  },
}
</script>

<style lang="scss" scoped>
.day-card {
  width: 100%;
  aspect-ratio: 1 / 1; /* makes it a square */
  min-width: 60px; /* optional minimum size */
  //max-width: 120px; /* optional max size */
  background-color: whitesmoke;
}

.day-content {
  width: 100%;
  height: 100%;
}

.moods {
  justify-content: start;
}

.mood-chip {
  font-size: 0.7rem;
}

.day-card.blank {
  background-color: #f5f5f5; /* lighter grey for blank */
}

.test-class-1 {
  border: 1px solid yellow;
}
</style>
