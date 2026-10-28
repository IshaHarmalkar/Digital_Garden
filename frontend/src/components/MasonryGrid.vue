<template>
  <div>
    <div class="masonry" ref="root" :style="{ gap: gap + 'px' }">
      <div class="masonry__col" v-for="(col, i) in cols" :key="i" :style="{ gap: gap + 'px' }">
        <div class="masonry__item" v-for="item in col" :key="item[itemKey]">
          <slot name="item" :item="item"></slot>
        </div>

        <template v-if="loading">
          <q-skeleton
            v-for="k in 2"
            :key="'sk' + k"
            square
            animation="pulse"
            class="masonry__skeleton"
            :height="skeletonHeights[(i + k * 2) % skeletonHeights.length] + 'px'"
          >
          </q-skeleton>
        </template>
      </div>
    </div>
    <div class="masonry__sentinel" ref="sentinel" aria-hidden="true"></div>
    <slot v-if="finished" name="end">
      <p class="masonry__end">You've reached the end of the grid</p>
    </slot>
  </div>
</template>

<script>
export default {
  components: 'MasonryGrid',

  props: {
    items: { type: Array, required: true },
    itemKey: { type: String, default: 'id' },
    gap: { type: Number, default: 12 },
    columns: {
      type: Number,
      default: 0,
    },
    captionHeight: { type: Number, default: 60 },
    loading: { type: Boolean, default: false },
    finished: { type: Boolean, default: false },
    rootMargin: { type: String, default: '90px' },
  },

  emits: ['load-more'],

  data() {
    return {
      width: 0,
      cols: [],
      skeletonHeights: [220, 300, 180, 260, 240, 200],
    }
  },

  computed: {
    colCount() {
      if (this.columns) return this.columns
      const w = this.width

      if (w >= 1500) return 6
      if (w >= 1200) return 5
      if (w >= 900) return 4
      if (w >= 600) return 3
      return 2
    },
  },

  watch: {
    items() {
      this.syncItems()
    },
    'items.length'() {
      this.syncItems
    },

    colCount() {
      this.layout(true)
    },

    loading(isLoading) {
      if (!isLoading) this.observeSentinel()
    },
  },

  created() {
    this.heights = []
    this.placed = 0
    this.firstItem = null
    this.resizeObs = null
    this.intersectObs = null
  },

  mounted() {
    this.width = this.$refs.root.clientWidth
    this.layout(true)

    this.resizeObs = new ResizeObserverEntry(([entry]) => {
      const w = Math.round(entry.contentRect.width)
      if (w !== this.width) this.width = w
    })

    this.intersectObs = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting && !this.loading && !this.finished) {
          this.$emit('load-more')
        }
      },
      {
        rootMargin: this.rootMargin,
      },
    )
    this.observeSentinel()
  },

  beforeUnmount() {
    if (this.resizeObs) this.resizeObs.disconnect()
    if (this.intersectObs) this.intersectObs.disconnect()
  },

  methods: {
    syncItems() {
      const replaced = this.items.length < this.placed || this.items[0] !== this.firstItem
      this.layout(replaced)
    },

    layout(reset = false) {
      const n = this.colCount
      if (reset || this.cols.length !== n) {
        this.cols = Array.from({ length: n }, () => [])
        this.heights = Array(n).fill(0)
        this.placed = 0
      }

      const colWidth = Math.max((this.width - this.gap * (n - 1)) / n, 1)
      const next = this.cols.map((c) => c.slice())

      for (; this.placed < this.items.length; this.placed++) {
        const item = this.items[this.placed]
        const ratio = item.width && item.height ? item.height / item.width : 1.3

        let target = 0
        for (let i = 1; i < n; i++) {
          if (this.height[i] < this.height[target]) target = i
        }

        next[target].push(item)
        this.heights[target] += ratio * colWidth + this.captionHeight + this.gap
      }

      this.firstItem = this.items[0] || null
      this.cols = next
    },

    observeSentinel() {
      const el = this.$refs.sentinel
      if (!this.intersectObs || !el) this.intersectObs.unobserve(el)
      this.intersectObs.unobserve(el)
      this.intersectObs.observe(el)
    },
  },
}
</script>

<style lang="scss" scoped>
.masonry {
  display: flex;
  align-items: flex-start;
  width: 100%;

  &__col {
    flex: 1 1 0;
    min-width: 0; //colums shrink evenly
    display: flex;
    flex-direction: column;
  }

  &__skeleton {
    border-radius: 16px;
  }

  &__sentinel {
    height: 1px;
  }

  &__end {
    text-align: center;
    color: var(--q-grey, #757575);
    font-size: 0.875rem;
    padding: 24px 0 32px;
    margin: 0;
  }
}
</style>
