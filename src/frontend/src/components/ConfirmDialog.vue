<template>
  <v-dialog
      v-model="show"
      persistent
      max-width="290"
  >
  <v-card>
    <v-card-title class="headline">
      Create enquiry
    </v-card-title>
    <v-card-text>Enquiry has been created successfully</v-card-text>
    <v-card-actions>
      <v-btn
          color="green darken-1"
          text
          @click="okHandler"
      >
        Ok
      </v-btn>
    </v-card-actions>
  </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'ConfirmDialog',
  props: {
    value: Boolean,
  },
  methods: {
    okHandler() {
      this.show = false;
      window.open('https://unbiased.co.uk', '_blank');
    },
  },
  computed: {
    show: {
      get () {
        return this.value
      },
      set (value) {
        this.$emit('input', value)
      }
    }
  }
}
</script>