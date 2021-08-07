<template>
  <v-app style="background:#eee">
      <HelloWorld v-if="!true"></HelloWorld>
      <Assi v-else></Assi>
  </v-app>
</template>

<script>
import Assi from './components/Assi';
import HelloWorld from './components/HelloWorld';

export default {
  name: 'App',

  components: {
    HelloWorld,
    Assi
  },

  data: () => ({
    //
  }),
};
</script>

