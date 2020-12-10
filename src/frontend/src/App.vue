<template>
  <div id="app">
    <v-app id="inspire">
      <div>
        <v-app-bar
            color="light-blue darken-4"
            dense
            dark
        >
          <v-app-bar-nav-icon></v-app-bar-nav-icon>
          <v-toolbar-title>Unbiased test</v-toolbar-title>
        </v-app-bar>
        <EnquiryForm/>
      </div>
    </v-app>
  </div>
</template>

<script>
import EnquiryForm from '@/components/EnquiryForm';

export default {
  name: 'App',
  components: {
    EnquiryForm,
  },
};
</script>
