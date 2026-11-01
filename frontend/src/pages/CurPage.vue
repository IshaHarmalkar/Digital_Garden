<template>
  <q-page class="pins-page">
    <header class="pins-page__bar">
      <div class="pins-page__top">
        <q-icon name="push_pin" color="primary" size="28px" class="pins-page__logo"></q-icon>
        <q-input
          dense
          rounded
          outlined
          clearable
          placeholder="Search ideas"
          class="pins-page__search"
          color="primary"
        >
          <template #prepend>
            <q-icon name="search"></q-icon>
          </template>
        </q-input>
      </div>

      <div class="pins-page__chips" role="tablist">
        <q-chip
          v-for="c in categories"
          :key="c"
          clickable
          :color="category === c ? 'primary' : 'grey-3'"
          :text-color="category === c ? 'white' : 'grey-9'"
          :label="c"
          role="tab"
          :aria-selected="category === c"
          @click="selectCategory(c)"
        ></q-chip>
      </div>
    </header>

    <div class="pins-page__grid">
      <MasonryGrid :items="pins" :loading="loading" :finished="finished" @load-more="loadMore">
        <template #item="{ item }">
          <PinCard
            :pin="item"
            :saved="savedIds.has(item.id)"
            @open="openPin"
            @toggle-save="toggleSave"
          >
          </PinCard>
        </template>
      </MasonryGrid>
    </div>
  </q-page>
</template>

<script>
import MasonryGrid from 'src/components/MasonryGrid.vue'
import PinCard from 'src/components/PinCard.vue'

const TITLES = [
  'Slow morning light',
  'Concrete and ferns',
  'Small kitchen, big ideas',
  'Weekend trail notes',
  'Ceramic shelf styling',
  'Rooftop herb garden',
  'Linen everything',
  'Harbor at dusk',
  'Sketchbook spreads',
  'Plan corner',
]

const AUTHORS = ['Maya', 'Leo', 'Priya', 'Sam', 'Noor', 'Chen']
const TONES = ['#f3d9d6', '#d9e4e0', '#e6dfd2', '#d8dde8', '#ecd6c9', '#dcdcdc']

const rand = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min

export default {
  name: 'PinsPage',
  components: { MasonryGrid, PinCard },

  data() {
    return {
      pins: [],
      loading: false,
      finished: false,
      category: 'All',
      categories: ['All', 'Home', 'Travel', 'Food', 'Art', 'Garden', 'Style'],
      savedIds: new Set(),
      nextId: 1,
    }
  },

  methods: {
    fetchPins(count = 20) {
      return new Promise((resolve) => {
        setTimeout(() => {
          resolve(
            Array.from({ length: count }, () => {
              const id = this.nextId++
              const width = 600
              const height = rand(360, 1000)
              return {
                id,
                width,
                height,
                src: `https://picsum.photos/seed/pin${id}/${width}/${height}`,
                title: TITLES[id % TITLES.length],
                author: AUTHORS[id % AUTHORS.length],
                color: TONES[id % TONES.length],
              }
            }),
          )
        }, 700)
      })
    },

    async loadMore() {
      if (this.loading || this.finished) return
      this.loading = true
      const batch = await this.fetchPins(20)
      this.pins = [...this.pins, ...batch] //appends, exisitng pins stay put

      this.loading = false
      if (this.pins.length >= 100) this.finished = true
    },

    selectCategory(name) {
      this.category = name
      this.nextId = 1
      this.finished = false
      this.pins = []
      this.loadMore()
    },

    toggleSave(pin) {
      if (this.savedIds.has(pin.id)) {
        this.savedIds.delete(pin.id)
      } else {
        this.savedIds.add(pin.id)
        this.$q.notify({
          message: 'Saved to your board',
          icon: 'bookmark',
          color: 'dark',
          position: 'bottom',
          timeout: 1600,
        })
      }
    },

    openPin(pin) {
      //eg this.$router.push({name: 'pin', params: {id: pin.id}})
      console.log('open', pin.id)
    },
  },
}
</script>

<style scoped lang="scss">
.pins-page {
  &__bar {
    position: sticky;
    top: 0;
    z-index: 10;
    background: var(--q-page-bg, #fff);
    padding: env(safe-area-inset-top, 0) 12px 0;
  }

  &__top {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
  }

  &__search {
    flex: 1;
  }

  &__chips {
    display: flex;
    gap: 2px;
    overflow-x: auto;
    padding: 8px 0 6px;
    scrollbar-width: none;
    &::-webkit-scrollbar {
      display: none;
    }
    :deep(.q-chip) {
      margin: 0 4px 0 0;
      flex: none;
      font-weight: 600;
    }
  }

  &__grid {
    padding: 4px 8px calc(16px + env(ssafe-area-inset-bottom, 0));
    max-width: 1800px;
    margin: 0 auto;
  }
}

@media (min-width: 600px) {
  .pins-page {
    &__bar {
      padding-left: 24px;
      padding-right: 24px;
    }
    &__grid {
      padding-left: 20px;
      padding-right: 20px;
    }
  }
}
</style>
