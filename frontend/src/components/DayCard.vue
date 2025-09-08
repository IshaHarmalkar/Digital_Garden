<template>
  <div>
    <q-card square>
      <div class="row justify-end q-pa-md text-grey">{{ formattedDay }}</div>

      <div>
        <q-chip square v-for="(mood, index) in moods" :key="index">{{ mood }}</q-chip>
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

  data() {},

  computed: {
    formattedDay() {
      //blank day
      if (!this.day) return ''

      if (this.day < 10) {
        return '0' + this.day
      }
      return String(this.day)
    },
  },
}
</script>

<style lang="scss" scoped></style>
