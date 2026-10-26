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
  name: 'MasonryGrid',
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
