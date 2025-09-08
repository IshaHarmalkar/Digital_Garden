<template>
  <div class="flex flex-center q-pa-md q-ma-md"></div>

  <div>
    <MonthlyView />
  </div>
</template>

<script>
import MonthlyView from 'src/components/MonthlyView.vue'

export default {
  name: 'CalendarPage',
  components: {
    MonthlyView,
  },

  data() {},
}
</script>

<style lang="scss" scoped></style>
