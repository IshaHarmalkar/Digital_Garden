<template>
  <div class="q-pa-md flex flex-center">
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
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'ReflectForm',
  data() {
    return {
      journal: '',
      gratitude: '',
      day: new Date().toISOString().slice(0, 10),
    }
  },
  methods: {
    async saveReflection() {
      try {
        await axios.post('http://localhost:8000/api/reflections', {
          journal: this.journal,
          gratitude: this.gratitude,
          day: this.day,
        })
        this.$q.notify({ type: 'positive', message: 'Reflection saved!' })
        this.resetForm()
      } catch (err) {
        console.log(err)
        this.$q.notify({ type: 'negative', message: 'Failed to save reflection' })
      }
    },
    resetForm() {
      this.journal = ''
      this.gratitude = ''
      this.day = new Date().toISOString().slice(0, 10)
    },
  },
}
</script>
