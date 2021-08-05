<template>
  <div class="hello">
    This isassiboard
    <button @click="createTeam">create Team</button>
  </div>
</template>

<script>
import api_call from "../Api"

export default {
  name: 'Assi',
  props: {
  },
  methods: {
    createTeam: function() {
        api_call("teams","post",{
                title:"Tam b"
            })
            .then(d=>console.log(d))
    }
  },
  mounted: function() {
    this.createTeam()
  }
}
</script>

