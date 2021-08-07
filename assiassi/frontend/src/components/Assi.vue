<template>
  <div v-if="is_board">
    <Board :teams="teams"></Board>
  </div>
  <div v-else>

    <v-app-bar
    flat
    app
    color="orange"
    >
      <v-container fluid class="py-0 fill-height">

        <img src="../assets/logo.png" style="margin-top:6px;margin-right:20px;" height="50">

        <div v-if="is_admin">
            <v-btn
              v-for="team in teams"
              :key="team.id"
              :outlined="!(current_team != undefined && team.id==current_team.id)"
              color="white"
              @click="setTeam(team)"
            >
              {{ team.letter }}
            </v-btn>

            <v-btn text @click="createTeam" color="white">+</v-btn> 
        </div>
        <h1 v-else style="text-align:center;width:100%;position:absolute"> {{current_team.title}}</h1>

        <v-spacer></v-spacer>

        <v-btn text v-if="is_admin" @click="deleteTeams">RESET</v-btn>
        <v-btn text v-if="is_admin" @click="board">BOARD</v-btn>
        <v-btn v-if="is_admin" text @click="lock">LOCK</v-btn> 
        <v-btn v-else text @click="unlock">UNLOCK</v-btn> 

        </v-container>
    </v-app-bar>



    <v-main>
        <v-container fluid v-if="current_team">

            <v-card style="max-width:800px;margin:auto" v-if="!is_admin && current_team.meal_vegans == null && current_team.meal_vegetarians == null && current_team.meal_omnivores == null">
                <h1>Anzahl Personen</h1>
                <v-text-field v-model="current_meal.meal_total" type="number" label="Anzahl aller Personen im Team (inklusive Dozent:in)"></v-text-field>
                <h1>Essgewohnheiten</h1>
                <v-row class="textcenter"><v-col cols="4">
                    <img src="../assets/art/vegan.png" height="100">
                    <v-text-field v-model="current_meal.meal_vegans" type="number" label="Anzahl Vegan"></v-text-field>
                </v-col><v-col cols="4">
                    <img src="../assets/art/vegetarian.png" height="100">
                    <v-text-field v-model="current_meal.meal_vegetarians" type="number" label="Anzahl Vegetarisch"></v-text-field>
                </v-col><v-col cols="4">
                    <img src="../assets/art/carnivor.png" height="100">
                    <v-text-field v-model="current_meal.meal_omnivores" type="number" label="Anzahl Omnivor"></v-text-field>
                </v-col></v-row>
                <h1>Unverträglichkeiten</h1>
                Beispiel:<br/>Es gibt eine Laktoseintolerante Veganerin und einen Erdnussallergischen Vegetarier, dann schreibt "1x Laktose (Vegan), 1x Ernussallergie (Vegetarisch)" 
                <v-text-field label="Aufzählung von Unverträglichkeiten"></v-text-field>

                <v-btn block v-if="current_meal.meal_total != parseInt(current_meal.meal_omnivores)
                + parseInt(current_meal.meal_vegans) + parseInt(current_meal.meal_vegetarians) " color="warning">Team Informationen speichern (Teamanzahl stimmt nicht)</v-btn>
                <v-btn block v-else color="primary" @click="storeMeals">Team Informationen speichern</v-btn>

                <br/>
            </v-card>

            <v-row v-else>
              <v-col cols="2" v-if="is_admin">

                <!-- Nächste Kamera Zeiten -->

                <v-card>
                    <h2>Kamera Equipment</h2>
                    <div v-for="check in camera_checks"
                        :key="JSON.stringify(check)" >
                        <img v-if="check.check == 'out'" src="../assets/art/checkout.jpg" width="50"> 
                        <img v-else src="../assets/art/checkin.jpg" width="50"> 
                        <v-chip :color="check.completed=='1' ? 'green' : timeOver(check.time) ? 'red' : 'gray'">{{check.time}}</v-chip>
                        <v-chip @click="setTeam(check.team)">{{check.team.letter}}</v-chip> 
                        <v-chip v-if="check.completed!='1'" color="green" @click="checkCompleted(check)">OK</v-chip>
                    </div>
                </v-card>

                <!-- SD Übersicht -->

                <v-card>
                    <div
                        v-for="{sd_status,label} in 'none:Karte in Kamera,queue:Warteschlange,process:Datenverarbeitung,ready:Schnittbereit'.split(',').map(string=>{let w=string.split(':');return {sd_status:w[0],label:w[1]}})"
                        :key="sd_status"
                    >
                        <h2>{{label}}</h2>
                        <v-chip v-for="team in teams.filter(team=>team.sd_status==sd_status)"
                            :key="'team_id_sd'+team.id" @click="setTeam(team)">
                            {{team.letter}}
                        </v-chip>
                    </div>
                </v-card>
            </v-col>
            <v-col :cols="is_admin?10:12">

                <!-- Team Admin -->
                <v-card v-if="is_admin"> <h1>{{current_team.title}}</h1> </v-card>
                <v-stepper v-model="current_team.sd_status" @change="updateTeam()" non-linear>
                  <v-stepper-header>

                    <v-stepper-step editable step="none" complete edit-icon="mdi-check"> Karte in Kamera </v-stepper-step> <v-divider></v-divider>
                    <v-stepper-step editable step="queue" :complete="current_team.sd_status!='none'" edit-icon="mdi-check"> Warteschlange </v-stepper-step> <v-divider></v-divider>
                    <v-stepper-step editable step="process" :complete="current_team.sd_status=='process'||current_team.sd_status=='ready'" edit-icon="mdi-check"> Datenverarbeitung </v-stepper-step> <v-divider></v-divider>
                    <v-stepper-step editable step="ready" complete-icon="mdi-check" :complete="current_team.sd_status=='ready'" edit-icon="mdi-check"> Schnittbereit </v-stepper-step> 

                  </v-stepper-header>
                </v-stepper>



                <!-- Wochenübersicht -->

                <v-row id="weeks">
                  <v-col cols="3" :style="'opacity:'+((day_of_week_index) < day_of_week ? '.5;pointer-events:none' : '1')" 
                    v-for="{day_of_week_name,day_of_week_index} in [{day_of_week_index:2,day_of_week_name:'Dienstag'},{day_of_week_index:3,day_of_week_name:'Mittwoch'},{day_of_week_index:4,day_of_week_name:'Donnerstag'},{day_of_week_index:5,day_of_week_name:'Freitag'}]" :key="day_of_week_index">

                    <v-card> <h2> {{day_of_week_name}} </h2> </v-card>

                    <!-- Essen angeben -->
                    <v-card>
                        <div :key="'meal'+day_of_week_index+'_'+meal_of_day_index" v-for="(meal_of_day,meal_of_day_index) in 'Frühstück,Mittagessen,Abendbrot'.split(',')">
                            <v-select @change="updateTeam" v-model="current_team['meal_'+(day_of_week_index)+'_'+meal_of_day_index]" :label="'Wo esst ihr '+meal_of_day+'?'" :items="'Im Camp,Am Set,Selbstversorgung'.split(',')"></v-select>
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
                            <v-text-field v-model="shooting.camera_checkout" :disabled="!is_admin" label="Checkout" hint="Format 23:59" clearable @change="updateTeam"></v-text-field>
                        </v-col><v-col cols="6">
                            <img src="../assets/art/checkin.jpg" width="50" style="float:left;margin-top:10px;margin-right:5px;">
                            <v-text-field v-model="shooting.camera_checkin" :disabled="!is_admin" label="Checkin" hint="Format 23:59" clearable @change="updateTeam"></v-text-field>
                        </v-col></v-row>

                        <h2>Studio Equipment</h2> 
                        <v-row><v-col cols="6">
                            <img src="../assets/art/checkout.jpg" width="50" style="float:left;margin-top:10px;margin-right:5px;">
                            <v-text-field v-model="shooting.studio_checkout" :disabled="!is_admin" label="Checkout" hint="Format 23:59" clearable @change="updateTeam"></v-text-field>
                        </v-col><v-col cols="6">
                            <img src="../assets/art/checkin.jpg" width="50" style="float:left;margin-top:10px;margin-right:5px;">
                            <v-text-field v-model="shooting.studio_checkin" :disabled="!is_admin" label="Checkin" hint="Format 23:59" clearable @change="updateTeam"></v-text-field>
                        </v-col></v-row>
                    </v-card>

                    <v-btn block @click="createShooting(day_of_week_index)" v-if="day_of_week_index >= day_of_week">
                    + Drehblock
                    </v-btn>

                  </v-col>
                </v-row>


            </v-col>
           </v-row>
        </v-container>
        <v-container v-else>
            <p class="mt-5 text-center" style="color:#fff4">Kein Team ausgewählt.</p>
        </v-container>
    </v-main>


  </div>
</template>

<script>
import VueCookies from 'vue-cookies'
import api_call from "../Api"
import Board from './Board';

export default {
  components:{Board},
  name: 'Assi',
  props: ["cookieTeam"],
  data: function() {
      return {
        current_team: null,
        current_meal: {meal_omnivores:null,meal_vegans:null,meal_vegetarians:null,meal_total:null,meal_annotation:""},
        current_team_meal: null,
        is_admin: false,
        is_board: false,
        teams: []
      }
  },
  computed: {
    day_of_week: function() {return 3},
    shootings: function() {
        let shootings = []
        this.teams.map(team=>team.shootings.map(shooting=>shootings.push(shooting)))
        return shootings
    },
    camera_checks: function() {
        let checks = [] 
        this.shootings.filter(shooting=>shooting.day_of_week == this.day_of_week).map((shooting)=>{
            if(shooting.camera_checkout!=null)
                checks.push({completed:shooting.camera_checkout_completed,check:"out",team:this.teamById(shooting.team_id),shooting_id:shooting.id,time:shooting.camera_checkout})
            if(shooting.camera_checkin!=null)
                checks.push({completed:shooting.camera_checkin_completed,check:"in",team:this.teamById(shooting.team_id),shooting_id:shooting.id,time:shooting.camera_checkin})
        })

        return this.timeSort(checks)
        
    }
  },
  methods: {
    storeMeals: function() {
        this.current_team.meal_vegans=this.current_meal.meal_vegans
        this.current_team.meal_vegetarians=this.current_meal.meal_vegetarians
        this.current_team.meal_omnivores=this.current_meal.meal_omnivores
        this.current_team.meal_annotation=this.current_meal.meal_annotation
        this.updateTeam()
    },
    board: function() {
        VueCookies.set('is_board' , true, "1year") 
        this.is_board = true
    },
    lock: function() {
        VueCookies.set('team.letter' , this.current_team.letter, "1year") 
        this.is_admin = false
    },
    unlock: function() {
        if(prompt("Admin PIN?") != "2412") return;
        VueCookies.set('team.letter' , undefined, 1) 
        this.is_admin = true
    },
    checkCompleted: function(check) {
        check.team.shootings.filter(shooting=>shooting.id==check.shooting_id)[0]["camera_check"+check.check+"_completed"]="1"
        this.updateTeam(check.team)
    },
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
        let timeOver = parseInt(time_parts[0]) < today.getHours() || (parseInt(time_parts[0]) == today.getHours() && parseInt(time_parts[1]) <= today.getMinutes() )
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
    updateTeam: function(team) {
        if(team==undefined || team.id==undefined)
            team=this.current_team
        api_call("team/"+team.id,"put",team)
        .then(d=>console.log(d))
    },
    loadData: function() {

        // show board if cookie present
        console.log("is_board",VueCookies.get('is_board'))
        if(VueCookies.get('is_board')) {
            this.is_board=true
        }

        api_call("teams","get")
        .then((d)=>{
            this.teams = d;
            let is_admin=true
            if(d.length>0) {
                if(this.current_team != null)
                    d=this.teams.filter(team=>team.id == this.current_team.id)
                else if(VueCookies.get('team.letter') != "") {
                    d=this.teams.filter(team=>team.letter == VueCookies.get('team.letter'))
                    // backend seeems locked
                }
                this.setTeam(this.teams[0])
                if(VueCookies.get('team.letter') != undefined && VueCookies.get('team.letter') != "")  {is_admin=false}
            }
            console.log("is ad",is_admin)
            this.is_admin = is_admin
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
.v-stepper.v-sheet {
    padding:0;border:none;box-shadow:none;
}
</style>
