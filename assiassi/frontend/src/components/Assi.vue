<template>
  <div class="hello">

    <v-app-bar
    flat
    app
    color="white"
    >
      <v-container fluid class="py-0 fill-height">

        <img src="../assets/logo.jpg" id="logo" height="56">

        <v-btn
          v-for="team in teams"
          :key="team.id"
          text
          :color="team==current_team?'primary':'normal'"
          @click="setTeam(team)"
        >
          {{ team.letter }}
        </v-btn>

        <v-btn text @click="createTeam">+</v-btn> 
        <v-btn color="red" @click="deleteTeams">Alle Teams löschen</v-btn>

        <v-spacer></v-spacer>

        </v-container>
    </v-app-bar>



    <v-main>
        <v-container fluid v-if="current_team">
        
            <v-row>
              <v-col cols="2">
                <v-card>
                    <div
                        v-for="sd_status in 'none,queue,process,ready'.split(',')"
                        :key="sd_status"
                    >
                        <h2>SD {{sd_status}}</h2>
                        <v-chip v-for="team in teams.filter(team=>team.sd_status==sd_status)"
                            :key="team" @click="setTeam(team)">
                            {{team.letter}}
                        </v-chip>
                    </div>
                </v-card>
            </v-col>
            <v-col cols="10">

                <v-card>
                    <h1>{{current_team.title}}</h1>

                    <v-radio-group @change="updateTeam()" v-model="current_team.sd_status">
                      <v-radio
                        v-for="sd_status in 'none,queue,process,ready'.split(',')"
                        :key="sd_status"
                        :label="'SD '+sd_status"
                        :value="sd_status"
                      ></v-radio>
                    </v-radio-group>
                </v-card>


                <v-row id="weeks">
                  <v-col cols="3" v-for="(day_of_week,day_of_week_index) in 'Dienstag,Mittwoch,Donnerstag,Freitag'.split(',')"
                    :key="day_of_week">
                    <v-card>
                    <h2> {{day_of_week}} </h2>
                    </v-card>
                    <v-card rounded="lg">

                        <div :key="'meal'+day_of_week_index+'_'+meal_of_day_index" v-for="(meal_of_day,meal_of_day_index) in 'Frühstück,Mittagessen,Abendbrot'.split(',')">
                            <v-select @change="updateTeam" v-model="current_team['meal_'+day_of_week+'_'+meal_of_day_index]" :label="'Wo esst ihr '+meal_of_day+'?'" :items="'Im Camp,Am Set,Selbstversorgung'.split(',')"></v-select>
                        </div>

                    </v-card>


                    <v-card v-for="shooting in current_team.shootings.filter(shooting=>shooting.day_of_week == day_of_week_index)" :key="shooting.id">
                        <h2>Drehblock</h2> 
                        <v-textarea
                          background-color="amber lighten-4"
                          color="orange orange-darken-4"
                          label="Label"
                          v-model="shooting.annotations"
                          @change="updateTeam"
                        ></v-textarea>
                    </v-card>

                    <v-btn block @click="createShooting(day_of_week_index)">
                    + Drehblock
                    </v-btn>

                  </v-col>
                </v-row>


            </v-col>
           </v-row>
        </v-container>
        <v-container v-else>Kein Team ausgewählt.</v-container>
    </v-main>


  </div>
</template>

<script>
import api_call from "../Api"

export default {
  name: 'Assi',
  props: { },
  data: function() {
      return {
        current_team: null,
        current_team_meal: null,
        teams: []
      }
  },
  methods: {
    setTeam: function(team) {
        this.current_team=team
    },
    createTeam: function() {
        let letter = prompt("Team Buchstabe?").toUpperCase()
        let title = prompt("Team Titel?","Team "+letter)

        api_call("teams","post",{
            title,
            letter
        })
        .then(()=>this.loadData())
    },
    createShooting: function(day_of_week) {
        api_call("team/"+this.current_team.id+"/shooting","post",{team_id:this.current_team.id,day_of_week})
        .then(()=>this.loadData())
    },
    deleteTeams: function() {
        if(confirm("Sicher? Wirklich alle Teams löschen?"))
            api_call("teams","delete")
            .then(()=>this.loadData())
    },
    updateTeam: function() {
        api_call("team/"+this.current_team.id,"put",this.current_team)
        .then(d=>console.log(d))
    },
    loadData: function() {
        api_call("teams","get")
        .then((d)=>{
            this.teams = d;
            if(d.length>0) {

                if(this.current_team != null)
                    d=this.teams.filter(team=>team.id == this.current_team.id)
                this.setTeam(d[0])
            }
        })
    }
  },
  mounted: function() {
    this.loadData()
  }
}
</script>

<style>
#logo {
    box-shadow:0 0 5px #ccc;
    border-radius:50%;
}
.v-app-bar button {
    margin-left:10px;
}
main .v-sheet {
    padding:10px 20px;
}
#weeks {
    margin-top:5px;
}
#weeks .v-sheet, .col-3 > button {
    margin-top:5px;
}
</style>
