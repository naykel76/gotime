<template>
  <transition name="fade">
    <div class="bx light modal posr" v-if="showing">
      <slot />
      <svg class="icon close posa txt-red x" aria-label="close" @click.prevent="close">
        <use xlink:href="/svg/nk_icon-defs.svg#icon-close"></use>
      </svg>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    showing: {
      required: true,
      type: Boolean,
    },
  },
  methods: {
    close() {
      this.$emit("close");
    },
  },
};
</script>

<style scoped>
.x{
  right: 1rem;
  top: 1rem;
}

.fade-enter-active,
.fade-leave-active {
  transition: all 0.4s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
