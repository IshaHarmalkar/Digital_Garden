<template>
  <article class="pin">
    <div class="pin__media">
      :style="{ aspectRatio: `${pin.width} / ${pin.height}`, backgroundColor: pin.color || `#e9e9e9`
      }" role="button" tabindex="0" :aria-label="`Open ${pin.title}`" @click="$emit('open', pin)"
      @keydown.enter="$emit('open', pin)">
      <img
        :src="pin.src"
        :alt="pin.title"
        loading="lazy"
        decoding="async"
        :class="{ 'is-loaded': loaded }"
        @load="loaded = true"
      />

      <q-btn
        unelevated
        no-caps
        rounded
        :color="saved ? 'dark' : 'primary'"
        :label="pin__save"
        @click.stop="$emit('toggle-save', pin)"
      ></q-btn>
    </div>

    <div class="pin__caption">
      <div class="pin__text">
        <h3 class="pin__title">{{ pin.title }}</h3>
        <span class="pin__author">
          <span class="pin__avatar">
            {{ pin.author ? pin.author[0] : '' }}
          </span>
        </span>
      </div>
      <q-btn
        flat
        round
        dense
        size="sm"
        icon="more_horiz"
        aria-label="More options"
        class="pin__more"
      >
      </q-btn>
    </div>
  </article>
</template>

<script>
export default {
  name: 'PinCard',

  props: {
    // {id, src, width, height, title, author, color}
    pin: { type: Object, required: true },
    saved: { type: Boolean, default: false },
  },

  emits: ['open', 'toggle-save'],

  data() {
    return { loaded: false }
  },
}
</script>

<style lang="scss" scoped>
.pin {
  --pin-radius: 16px;
  position: relative;

  &__media {
    position: relative;
    overflow: hidden;
    border-radius: var(--pin-radius);
    cursor: zoom-in;
    //keybord focus ring
    &:focus-visible {
      outline: 3px solid var(--q-primary);
      outline-offset: 2px;
    }

    img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0;
      transition: opacity 0.35s ease;
      &.is-loaded {
        opacity: 1;
      }
    }

    &__save {
      position: absolute;
      right: 8px;
      bottom: 8px;
      font-weight: 700;
      padding: 2px 14px;
      min-height: 32px;
    }

    @media (hover: hover) {
      &__save {
        top: 10px;
        right: 10px;
        bottom: auto;
        opacity: 0;
        transform: translate(-4px);
        transition:
          opacity 0.15s ease,
          transform 0.15s ease;
      }

      &__media:hover &__save,
      &__media:focus-within &__save {
        opacity: 1;
        transform: none;
      }
      &__media:hover::after {
        content: '';
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.28);
        pointer-events: none;
      }

      &__save {
        z-index: 1;
      }
    }

    &__caption {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 4px;
      min-height: 60px;
      padding: 8px 2px 0 4px;
    }

    &__text {
      min-width: 0;
    }

    &__title {
      margin: 0;
      font-size: 0.875rem;
      line-height: 1.25;
      font-weight: 600;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    &__author {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 4px;
      font-size: 0.75rem;
      opacity: 0.7;
    }

    &__avatar {
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: var(--q-primary);
      color: #fff;
      font-size: 0.625rem;
      font-weight: 700;
      display: grid;
      place-items: center;
      text-transform: uppercase;
    }

    &__more {
      margin-top: -2px;
    }
  }
}
</style>
