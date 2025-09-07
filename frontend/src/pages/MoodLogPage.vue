<template>
  <q-page class="q-pa-md flex flex-center">
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
  </q-page>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useQuasar } from 'quasar'
import axios from 'axios'

export default {
  name: 'MoodLogPage',
  setup() {
    const $q = useQuasar()
    const moodTree = ref({})
    const flatMoods = ref([])
    const selectedPrimary = ref(null)
    const selectedSecondary = ref(null)
    const selectedTertiary = ref(null)
    const slot = ref(null)
    const entryDate = ref(new Date().toISOString().slice(0, 10))

    const slots = ['morning', 'afternoon', 'night']

    const primaryOptions = computed(() => Object.keys(moodTree.value))
    const secondaryOptions = computed(() =>
      selectedPrimary.value ? Object.keys(moodTree.value[selectedPrimary.value]) : [],
    )
    const tertiaryOptions = computed(() =>
      selectedPrimary.value && selectedSecondary.value
        ? moodTree.value[selectedPrimary.value][selectedSecondary.value].map((t) => ({
            label: t.tertiary,
            value: t.id,
          }))
        : [],
    )

    const saveMood = async () => {
      try {
        await axios.post('http://localhost:8000/api/mood-entries', {
          mood_id: selectedTertiary.value,
          slot: slot.value,
          entry_date: entryDate.value,
        })
        $q.notify({ type: 'positive', message: 'Mood saved!' })
        resetForm()
      } catch (err) {
        console.log(err)
        $q.notify({ type: 'negative', message: 'Failed to save mood' })
      }
    }

    const resetForm = () => {
      selectedPrimary.value = null
      selectedSecondary.value = null
      selectedTertiary.value = null
      slot.value = null
    }

    onMounted(async () => {
      const treeRes = await axios.get('http://localhost:8000/api/moods/tree')
      moodTree.value = treeRes.data
      const flatRes = await axios.get('http://localhost:8000/api/moods')
      flatMoods.value = flatRes.data
    })

    return {
      selectedPrimary,
      selectedSecondary,
      selectedTertiary,
      primaryOptions,
      secondaryOptions,
      tertiaryOptions,
      slot,
      slots,
      entryDate,
      saveMood,
    }
  },
}
</script>
