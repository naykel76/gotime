<template>

  <div class="pxy flash" :class="{'danger': type === 'danger', 'success': type ==='success', 'warning': type ==='warning'}" role="alert" v-show="showMsg">
    {{ msg }}
  </div>

</template>

<script>
export default {
  props: ["msg"],

  data() {
    return {
      showMsg: false,
      type: 'success',
    };
  },

  created() {
    // if there is a msg, then show it and close it
    if (this.msg) {
      this.show(this.msg);
    }
  },

  methods: {
    show(msg) {
      this.showMsg = true;
      this.hide();
    },

    hide() {
      setTimeout(() => {
        this.showMsg = false;
      }, 3000);
    },
  },
};
</script>

<style>
.flash {
  position: absolute;
  bottom: 2rem;
  right: 2rem;
  z-index: 1000;
  text-align: center;
  max-width: 400px;
}
</style>
