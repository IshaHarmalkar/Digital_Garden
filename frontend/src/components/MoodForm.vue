<template>
  <div>
    <q-card class="q-pa-lg q-ma-md" style="max-width: 500px; width: 100%">
      <q-card-section>
        <div class="text-h6 text-center">Log Your Mood</div>
      </q-card-section>

      <q-separator />

      <q-card-section>
        <!-- Mood Selection -->
        <q-select
          v-model="selectedPrimary"
          :options="primaryOptions"
          label="Primary Mood"
          outlined
          dense
          class="q-mb-md"
        />

        <q-select
          v-if="selectedPrimary"
          v-model="selectedSecondary"
          :options="secondaryOptions"
          label="Secondary Mood"
          outlined
          dense
          class="q-mb-md"
        />

        <q-select
          v-if="selectedSecondary"
          v-model="selectedTertiary"
          :options="tertiaryOptions"
          label="Tertiary Mood"
          outlined
          dense
          emit-value
          map-options
          class="q-mb-md"
        />

        <!-- Slot Selection -->
        <q-select
          v-model="slot"
          :options="slots"
          label="Time of Day"
          outlined
          dense
          class="q-mb-md"
        />

        <!-- Date Picker -->
        <q-input
          v-model="entryDate"
          type="date"
          label="Entry Date"
          outlined
          dense
          class="q-mb-md"
        />

        <q-btn
          label="Save Mood"
          color="primary"
          class="full-width"
          :disable="!selectedTertiary || !slot"
          @click="saveMood"
        />
      </q-card-section>
    </q-card>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'MoodForm',
  data() {
    return {
      moodTree: {},
      flatMoods: [],
      selectedPrimary: null,
      selectedSecondary: null,
      selectedTertiary: null,
      slot: null,
      entryDate: new Date().toISOString().slice(0, 10),
      slots: ['morning', 'afternoon', 'night'],
    }
  },
  computed: {
    primaryOptions() {
      return Object.keys(this.moodTree || {})
    },
    secondaryOptions() {
      return this.selectedPrimary ? Object.keys(this.moodTree[this.selectedPrimary] || {}) : []
    },
    tertiaryOptions() {
      return this.selectedPrimary && this.selectedSecondary
        ? this.moodTree[this.selectedPrimary][this.selectedSecondary].map((t) => ({
            label: t.tertiary,
            value: t.id,
          }))
        : []
    },
  },
  methods: {
    async saveMood() {
      try {
        await axios.post('http://localhost:8000/api/mood-entries', {
          mood_id: this.selectedTertiary,
          slot: this.slot,
          entry_date: this.entryDate,
        })
        this.$q.notify({ type: 'positive', message: 'Mood saved!' })
        this.resetForm()
      } catch (err) {
        console.log(err)
        this.$q.notify({ type: 'negative', message: 'Failed to save mood' })
      }
    },
    resetForm() {
      this.selectedPrimary = null
      this.selectedSecondary = null
      this.selectedTertiary = null
      this.slot = null
    },
  },
  async mounted() {
    const treeRes = await axios.get('http://localhost:8000/api/moods/tree')
    this.moodTree = treeRes.data
    const flatRes = await axios.get('http://localhost:8000/api/moods')
    this.flatMoods = flatRes.data
  },
}
</script>
