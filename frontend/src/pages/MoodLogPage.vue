<template>
  <q-page class="q-pa-md">
    <div class="text-h5 q-mb-md">Log Your Mood</div>

    <!-- Slot Selection -->
    <q-select
      v-model="form.slot"
      :options="slotOptions"
      label="Select Time of Day"
      outlined
      class="q-mb-md"
    />

    <!-- Primary -->
    <q-select
      v-model="selectedPrimary"
      :options="primaryOptions"
      label="Primary Feeling"
      outlined
      class="q-mb-md"
      @update:model-value="onPrimarySelect"
    />

    <!-- Secondary -->
    <q-select
      v-if="secondaryOptions.length"
      v-model="selectedSecondary"
      :options="secondaryOptions"
      label="Secondary Feeling"
      outlined
      class="q-mb-md"
      @update:model-value="onSecondarySelect"
    />

    <!-- Tertiary -->
    <q-select
      v-if="tertiaryOptions.length"
      v-model="selectedTertiary"
      :options="tertiaryOptions"
      option-label="tertiary"
      option-value="id"
      label="Tertiary Feeling"
      outlined
      class="q-mb-md"
      @update:model-value="onTertiarySelect"
    />

    <!-- Date Picker -->
    <q-input v-model="form.entry_date" label="Date" outlined type="date" class="q-mb-md" />

    <!-- Submit -->
    <q-btn
      label="Save Mood"
      color="primary"
      class="full-width"
      :disable="!form.mood_id || !form.slot"
      @click="saveMood"
    />
  </q-page>
</template>

<script>
export default {
  name: 'MoodLogPage',
  data() {
    return {
      moods: {},
      primaryOptions: [],
      secondaryOptions: [],
      tertiaryOptions: [],
      selectedPrimary: null,
      selectedSecondary: null,
      selectedTertiary: null,
      form: {
        slot: null,
        mood_id: null,
        entry_date: new Date().toISOString().split('T')[0],
      },
      slotOptions: ['morning', 'afternoon', 'night'],
    }
  },
  async mounted() {
    try {
      const { data } = await this.$api.get('/moods/tree')
      this.moods = data
      this.primaryOptions = Object.keys(data)
    } catch (err) {
      console.log(err)

      this.$q.notify({ type: 'negative', message: 'Failed to load moods' })
    }
  },
  methods: {
    onPrimarySelect() {
      this.secondaryOptions = Object.keys(this.moods[this.selectedPrimary])
      this.selectedSecondary = null
      this.selectedTertiary = null
      this.tertiaryOptions = []
      this.form.mood_id = null
    },
    onSecondarySelect() {
      this.tertiaryOptions = this.moods[this.selectedPrimary][this.selectedSecondary]
      this.selectedTertiary = null
      this.form.mood_id = null
    },
    onTertiarySelect(mood) {
      this.form.mood_id = mood.id
    },
    async saveMood() {
      try {
        await this.$api.post('/mood-entries', this.form)
        this.$q.notify({ type: 'positive', message: 'Mood saved successfully!' })
        this.resetForm()
      } catch (err) {
        console.log(err)
        this.$q.notify({ type: 'negative', message: 'Error saving mood' })
      }
    },
    resetForm() {
      this.form.slot = null
      this.form.mood_id = null
      this.form.entry_date = new Date().toISOString().split('T')[0]
      this.selectedPrimary = null
      this.selectedSecondary = null
      this.selectedTertiary = null
      this.secondaryOptions = []
      this.tertiaryOptions = []
    },
  },
}
</script>
