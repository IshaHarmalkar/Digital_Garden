<template>
  <q-page class="q-pa-md flex flex-center">
    <q-card class="q-pa-lg q-ma-md" style="max-width: 600px; width: 100%">
      <q-card-section>
        <div class="text-h6 text-center">Daily Reflection</div>
      </q-card-section>

      <q-separator />

      <q-card-section>
        <!-- Journal note -->
        <q-input
          v-model="journal"
          type="textarea"
          label="Journal Note"
          outlined
          autogrow
          class="q-mb-md"
        />

        <!-- Gratitude -->
        <q-input
          v-model="gratitude"
          type="textarea"
          label="Gratitude"
          outlined
          autogrow
          class="q-mb-md"
        />

        <!-- Date -->
        <q-input v-model="day" type="date" label="Day" outlined dense class="q-mb-md" />

        <q-btn
          label="Save Reflection"
          color="primary"
          class="full-width"
          :disable="!journal"
          @click="saveReflection"
        />
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import axios from 'axios'

export default {
  name: 'ReflectionPage',
  setup() {
    const $q = useQuasar()
    const journal = ref('')
    const gratitude = ref('')
    const day = ref(new Date().toISOString().slice(0, 10))

    const saveReflection = async () => {
      try {
        await axios.post('http://localhost:8000/api/reflections', {
          journal: journal.value,
          gratitude: gratitude.value,
          day: day.value,
        })
        $q.notify({ type: 'positive', message: 'Reflection saved!' })
        resetForm()
      } catch (err) {
        console.log(err)
        $q.notify({ type: 'negative', message: 'Failed to save reflection' })
      }
    }

    const resetForm = () => {
      journal.value = ''
      gratitude.value = ''
      day.value = new Date().toISOString().slice(0, 10)
    }

    return {
      journal,
      gratitude,
      day,
      saveReflection,
    }
  },
}
</script>
