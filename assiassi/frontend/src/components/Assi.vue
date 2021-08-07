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

        <v-btn v-if="is_admin" text @click="createTeam">+</v-btn> 
        <v-btn v-if="is_admin" color="red" @click="deleteTeams">Alle Teams löschen</v-btn>

        <v-spacer></v-spacer>

        </v-container>
    </v-app-bar>



    <v-main>
        <v-container fluid v-if="current_team">
        
            <v-row>
              <v-col cols="2">

                <!-- Nächste Kamera Zeiten -->

                <v-card>
                    <h2>Kamera Equipment</h2>
                    <div v-for="check in camera_checks"
                        :key="JSON.stringify(check)" >
                        <img v-if="check.check == 'out'" src="../assets/art/checkout.jpg" width="50"> 
                        <img v-else src="../assets/art/checkin.jpg" width="50"> 
                        <v-chip :color="timeOver(check.time) ? 'orange' : 'gray'">{{check.time}}</v-chip>
                        <v-chip @click="setTeam(check.team)">{{check.team.letter}}</v-chip> 
                        <v-chip v-if="false" color="green" @click="console.log()">OK</v-chip>
                    </div>
                </v-card>

                <!-- SD Übersicht -->

                <v-card>
                    <div
                        v-for="sd_status in 'none,queue,process,ready'.split(',')"
                        :key="sd_status"
                    >
                        <h2>SD {{sd_status}}</h2>
                        <v-chip v-for="team in teams.filter(team=>team.sd_status==sd_status)"
                            :key="'team_id_sd'+team.id" @click="setTeam(team)">
                            {{team.letter}}
                        </v-chip>
                    </div>
                </v-card>
            </v-col>
            <v-col cols="10">

                <v-card>

                    <!-- Team Admin -->

                    <h1>{{current_team.title}}</h1>

                    <v-radio-group @change="updateTeam()" v-model="current_team.sd_status">
                      <v-radio
                        v-for="sd_status in 'none,queue,process,ready'.split(',')"
                        :key="'admin_sd_'+sd_status"
                        :label="'SD '+sd_status"
                        :value="sd_status"
                      ></v-radio>
                    </v-radio-group>
                </v-card>


                <!-- Wochenübersicht -->

                <v-row id="weeks">
                  <v-col cols="3" v-for="(day_of_week,day_of_week_index) in 'Dienstag,Mittwoch,Donnerstag,Freitag'.split(',')" :key="day_of_week">

                    <v-card> <h2> {{day_of_week}} </h2> </v-card>

                    <!-- Essen angeben -->
                    <v-card>
                        <div :key="'meal'+day_of_week_index+'_'+meal_of_day_index" v-for="(meal_of_day,meal_of_day_index) in 'Frühstück,Mittagessen,Abendbrot'.split(',')">
                            <v-select @change="updateTeam" v-model="current_team['meal_'+(day_of_week_index+2)+'_'+meal_of_day_index]" :label="'Wo esst ihr '+meal_of_day+'?'" :items="'Im Camp,Am Set,Selbstversorgung'.split(',')"></v-select>
                        </div>
                    </v-card>


                    <!-- alle Drehblöcke anzeigen -->
                    <v-card v-for="shooting in current_team.shootings.filter(shooting=>shooting.day_of_week == day_of_week_index)" :key="shooting.id">
                        <h2>Drehblock</h2> 

                        <img src="../assets/art/location.png" width="50" style="float:left;margin-top:10px;margin-right:5px;">
                        <v-text-field v-model="shooting.location" label="Drehort" hint="Wo dreht ihr? (Scheune, Studio, Wald, ...)" clearable @change="updateTeam"></v-text-field>

                        <v-row><v-col cols="6">
                            <img src="../assets/art/feuerwehr.png" width="50" style="float:left;margin-top:10px;margin-right:5px;">
                            <v-text-field v-model="shooting.departure" label="Abfahrt" hint="Format 23:59" clearable @change="updateTeam"></v-text-field>
                        </v-col><v-col cols="6">
                            <img src="../assets/art/feuerwehr.png" width="50" style="float:left;margin-top:10px;margin-right:5px;">
                            <v-text-field v-model="shooting.arrival" label="Rückfahrt" hint="Format 23:59" clearable @change="updateTeam"></v-text-field>
                        </v-col></v-row>

                        <v-textarea
                        outlined
                          label="Freier Text"
                          height="80"
                          v-model="shooting.annotations"
                          @change="updateTeam"
                        ></v-textarea>


                        <h2>Kamera Equipment</h2> 
                        <v-row><v-col cols="6">
                            <img src="../assets/art/checkout.jpg" width="50" style="float:left;margin-top:10px;margin-right:5px;">
                            <v-text-field v-model="shooting.camera_checkout" label="Checkout" hint="Format 23:59" clearable @change="updateTeam"></v-text-field>
                        </v-col><v-col cols="6">
                            <img src="../assets/art/checkin.jpg" width="50" style="float:left;margin-top:10px;margin-right:5px;">
                            <v-text-field v-model="shooting.camera_checkin" label="Checkin" hint="Format 23:59" clearable @change="updateTeam"></v-text-field>
                        </v-col></v-row>

                        <h2>Studio Equipment</h2> 
                        <v-row><v-col cols="6">
                            <img src="../assets/art/checkout.jpg" width="50" style="float:left;margin-top:10px;margin-right:5px;">
                            <v-text-field v-model="shooting.studio_checkout" label="Checkout" hint="Format 23:59" clearable @change="updateTeam"></v-text-field>
                        </v-col><v-col cols="6">
                            <img src="../assets/art/checkin.jpg" width="50" style="float:left;margin-top:10px;margin-right:5px;">
                            <v-text-field v-model="shooting.studio_checkin" label="Checkin" hint="Format 23:59" clearable @change="updateTeam"></v-text-field>
                        </v-col></v-row>
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
  props: ["cookieTeam"],
  data: function() {
      return {
        current_team: null,
        current_team_meal: null,
        teams: []
      }
  },
  computed: {
    is_admin: function() {return this.cookieTeam < 1 || this.cookieTeam == undefined},
    shootings: function() {
        let shootings = []
        this.teams.map(team=>team.shootings.map(shooting=>shootings.push(shooting)))
        return shootings
    },
    camera_checks: function() {
        let checks = [] 
        this.shootings.map((shooting)=>{
            if(shooting.camera_checkout!=null)
                checks.push({check:"out",team:this.teamById(shooting.team_id),time:shooting.camera_checkout})
            if(shooting.camera_checkin!=null)
                checks.push({check:"in",team:this.teamById(shooting.team_id),time:shooting.camera_checkin})
        })

        return this.timeSort(checks)
        
    }
  },
  methods: {
    minsToTime: function(time_mins) {
    return Math.floor(time_mins/60)+":"+time_mins%60
    },
    timeToMins: function(time_string) {
        let time_parts = time_string.split(":")
        return parseInt(time_parts[0])*60+parseInt(time_parts[1])
    },
    teamById: function(team_id) {return this.teams.filter(team=>team.id==team_id)[0]},
    setTeam: function(team) {
        this.current_team=team
    },
    timeSort: function(r) {
        r.sort(function(a, b) {
          return this.timeToMins(a.time)<this.timeToMins(b.time) ? -1 : 1;
        }.bind(this));
        return r
    },
    timeOver: function(time_string) {
        let time_parts = time_string.split(":")
        var today = new Date();
        console.log("Ich möchte jetzt Zeiten vergleichen")
        console.log("Das ist meine Eingabeuhrzeit: "+time_string)
        console.log("So habe ich ihn zerlegt: ",parseInt(time_parts[0]) +":"+parseInt(time_parts[1]) )
        console.log("Das ist die jetzige Uhrzeit: "+today.getHours()+":"+today.getMinutes())
        let timeOver = parseInt(time_parts[0]) <= today.getHours() && parseInt(time_parts[1]) <= today.getMinutes()  
        if(timeOver) { console.log("Ich komme zur Erkenntnis, dass die Zeit vorbei ist, weil "+parseInt(time_parts[0]) +">"+ today.getHours()) }
        else { console.log("Ich komme zur Erkenntnis, dass die NICHT Zeit vorbei ist, weil "+parseInt(time_parts[0]) +"<"+ today.getHours()) }
        return timeOver
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
.v-card {
    margin-bottom:10px;
}
.v-card .v-chip {
    margin-right:5px;
}
</style>
