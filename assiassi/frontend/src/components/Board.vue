<template>
    <v-container fluid>
        <v-row>
            <v-col cols="4" v-for="team in teams" :key="team.id">
                <v-card rounded="lg" class="border-warning">
                    <v-container fluid>
                        <v-row>
                            <v-col cols="4">
                                <div class="team-btn"> {{team.letter}} </div>
                            </v-col>
                            <v-col cols="4">
                                <b>Kamera</b>
                                <div class="text-warning"> &larr; 20:00 Uhr </div>
                                &rarr; 20:00 Uhr
                            </v-col>
                            <v-col cols="4">
                                <b>Studio</b><br/>
                                &larr; 20:00 Uhr
                                <br>
                                &rarr; 20:00 Uhr
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="4" style="padding-left:25px">
                                <b>SD Karte</b><br/>
                                {{team.sd_status}}.
                            </v-col>
                            <v-col cols="4">
                                <b>Fahr Dispo</b><br/>
                                &larr; 20:00 Uhr
                                <br>
                                &rarr; 20:00 Uhr
                            </v-col>
                            <v-col cols="4">
                                <b>Drehort</b><br/>
                                Scheune
                            </v-col>
                        </v-row>
                    </v-container>
                    <div style="background:orange;padding:10px 20px">{{team.title}}</div>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
  export default {
    name: 'Board',
    props: ["teams"],
    computed: {
        
    },
    data: () => ({
    }),
  }
</script>

<style>
.text-warning {
    color:orange;
    animation:3s breathe infinite ease-in-out;
    font-weight:bold;
}
.border-warning .team-btn {
    background-image:url(../assets/art/button_warning.png);
    animation:3s breathe infinite ease-in-out;
}
.border-danger .team-btn {
    background-image:url(../assets/art/button_danger.png);
}
@keyframes breathe {
    0% {opacity:1}
    10% {opacity:.5}
    20% {opacity:1}
    30% {opacity:.5}
    40% {opacity:1}
    100% {opacity:1}
}
.team-btn {
    height:100px;
    color:white;
    text-align:center;
    font-size:50px;
    padding-top:12px;
    width:100px;
    background-size:cover;
    background-image:url(../assets/art/button_primary.png);
}
</style>
