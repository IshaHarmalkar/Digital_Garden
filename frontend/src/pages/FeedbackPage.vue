<template>
  <q-page class="feedback-page">
    <div class="q-pa-lg">
      <!-- Page Title -->
      <h1 class="page-title">Feedback for Newsletter #{{ newsletter?.id }}</h1>

      <!-- Success Message -->
      <q-banner v-if="successMessage" class="text-positive q-mb-md" rounded dense>
        <template v-slot:avatar>
          <q-icon name="check_circle" color="positive" />
        </template>
        {{ successMessage }}
      </q-banner>

      <!-- Loading State -->
      <div v-if="loading" class="row justify-center q-my-lg">
        <q-spinner-dots size="50px" color="primary" />
      </div>

      <!-- Error State -->
      <q-banner v-else-if="error" class="text-negative q-mb-md" rounded dense>
        <template v-slot:avatar>
          <q-icon name="error" color="negative" />
        </template>
        {{ error }}
      </q-banner>

      <!-- Feedback Form -->
      <q-form v-else @submit.prevent="submitFeedback" class="feedback-form">
        <div
          v-for="(item, index) in curatedItems"
          :key="`${item.type}-${item.model?.id}`"
          class="feedback-card q-mb-lg"
        >
          {{ index }}
          <q-card class="full-width" flat bordered>
            <q-card-section class="row q-col-gutter-lg">
              <!-- Left Column: Content -->
              <div class="col-12 col-md-8">
                <div class="content-section">
                  <h3 class="content-title">{{ item.type }}</h3>

                  <!-- Native Content -->
                  <div v-if="item.type === 'Native'">
                    <p class="content-text">{{ item.model?.content }}</p>

                    <q-img
                      v-if="item.model?.image_url"
                      :src="item.model.image_url"
                      alt="Native Content"
                      class="content-image q-mb-md"
                      style="max-height: 300px; border-radius: 8px"
                      fit="cover"
                    />

                    <q-btn
                      v-if="item.model?.url"
                      :href="item.model.url"
                      target="_blank"
                      color="primary"
                      outline
                      no-caps
                      class="q-mt-md"
                    >
                      Visit Link
                    </q-btn>
                  </div>

                  <!-- Notion Content -->
                  <div v-else-if="item.type === 'Notion'">
                    <a :href="item.model?.url" target="_blank" class="content-link">
                      {{ item.model?.title }}
                    </a>
                  </div>

                  <!-- Pinterest Content -->
                  <div v-else-if="item.type === 'Pinterest'">
                    <div class="pinterest-embed q-mb-md" v-html="item.model?.embed_code"></div>

                    <div class="row items-center q-gutter-sm">
                      <q-icon name="push_pin" size="sm" />
                      <a :href="item.model?.pin_link" target="_blank" class="content-link">
                        Go to Pin
                      </a>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Right Column: Feedback -->
              <div class="col-12 col-md-4">
                <q-separator vertical class="gt-sm" />
                <q-separator horizontal class="lt-md q-my-md" />

                <div class="feedback-sidebar">
                  <h4 class="sidebar-title">Your Feedback</h4>

                  <!-- Like Button -->
                  <div class="q-mb-lg">
                    <q-btn
                      :color="isLiked(item) ? 'pink' : 'grey-5'"
                      :text-color="isLiked(item) ? 'white' : 'grey-8'"
                      :outline="!isLiked(item)"
                      @click="toggleLike(item)"
                      class="full-width"
                      no-caps
                    >
                      <q-icon name="favorite" class="q-mr-sm" />
                      Like
                    </q-btn>
                  </div>

                  <!-- See Again Soon -->
                  <div class="q-mb-lg">
                    <q-checkbox
                      v-model="feedbackData[item.type][item.model?.id].see_again_soon"
                      label="❓ See Again Soon"
                      color="primary"
                      class="full-width"
                    />
                  </div>
                </div>
              </div>
            </q-card-section>
          </q-card>
        </div>

        <!-- Submit Button -->
        <div class="row justify-center q-mt-xl">
          <q-btn
            type="submit"
            color="primary"
            size="lg"
            :loading="submitting"
            :disable="submitting"
            class="q-px-xl"
            no-caps
          >
            Submit Feedback
          </q-btn>
        </div>
      </q-form>
    </div>
  </q-page>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { api } from 'src/boot/axios'
import { Notify } from 'quasar'

// Route params
const route = useRoute()
const newsletterId = computed(() => route.params.id)

// Reactive data
const loading = ref(true)
const submitting = ref(false)
const error = ref('')
const successMessage = ref('')
const newsletter = ref(null)
const curatedItems = ref([])
const feedbackData = reactive({
  Native: {},
  Notion: {},
  Pinterest: {},
})

// Initialize feedback data for an item
const initializeFeedbackData = (item) => {
  const stats = item.model?.stats || {}
  const type = item.type
  const id = item.model?.id

  if (!feedbackData[type]) {
    feedbackData[type] = {}
  }

  feedbackData[type][id] = {
    like_count: stats.like_count || 0,
    see_again_soon: stats.see_again_soon || false,
    stat_id: stats.id || null,
  }
}

// Check if item is liked
const isLiked = (item) => {
  const type = item.type
  const id = item.model?.id
  return feedbackData[type]?.[id]?.like_count > 0
}

// Toggle like status
const toggleLike = (item) => {
  const type = item.type
  const id = item.model?.id

  if (feedbackData[type]?.[id]) {
    feedbackData[type][id].like_count = feedbackData[type][id].like_count > 0 ? 0 : 1
  }
}

// Fetch newsletter data
const fetchNewsletterData = async () => {
  try {
    loading.value = true
    error.value = ''

    const response = await api.get(`/api/newsletter/${newsletterId.value}/feedback`)

    if (response.data.success) {
      newsletter.value = response.data.data.newsletter
      curatedItems.value = response.data.data.curated

      // Initialize feedback data for each item
      curatedItems.value.forEach(initializeFeedbackData)
    } else {
      error.value = 'Failed to load newsletter data'
    }
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to load newsletter data'
  } finally {
    loading.value = false
  }
}

// Submit feedback
const submitFeedback = async () => {
  try {
    submitting.value = true

    const response = await api.post('/api/newsletter/feedback', {
      stats: feedbackData,
    })

    if (response.data.success) {
      successMessage.value = response.data.message

      // Show success notification
      Notify.create({
        type: 'positive',
        message: 'Feedback saved successfully!',
        position: 'top-right',
      })

      // Clear success message after 5 seconds
      setTimeout(() => {
        successMessage.value = ''
      }, 5000)
    }
  } catch (err) {
    const message = err.response?.data?.message || 'Failed to save feedback'

    Notify.create({
      type: 'negative',
      message,
      position: 'top-right',
    })
  } finally {
    submitting.value = false
  }
}

// Lifecycle
onMounted(() => {
  if (newsletterId.value) {
    fetchNewsletterData()
  }
})
</script>

<style lang="scss" scoped>
.feedback-page {
  background: #f9fafb;
  min-height: 100vh;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  color: #1f2937;
}

.feedback-card {
  transition: all 0.3s ease;

  &:hover {
    transform: translateY(-2px);
  }
}

.content-section {
  .content-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #111827;
  }

  .content-text {
    margin-bottom: 1rem;
    color: #374151;
    line-height: 1.6;
  }

  .content-link {
    color: #2563eb;
    text-decoration: none;
    font-size: 1.125rem;
    font-weight: 500;

    &:hover {
      text-decoration: underline;
      color: #1d4ed8;
    }
  }
}

.feedback-sidebar {
  .sidebar-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #374151;
  }
}

.pinterest-embed {
  :deep(iframe) {
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  }
}

// Responsive adjustments
@media (max-width: $breakpoint-sm-max) {
  .page-title {
    font-size: 1.25rem;
  }
}
</style>
