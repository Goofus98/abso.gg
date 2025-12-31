<template>
  <v-app-bar app color="#101010" height="60" class="mb-6 pa-0 ma-0">
    
    <!-- Left items -->
    <v-toolbar-items>
      <div class="d-flex align-center">
        <img class="logo" src="/images/abso_logo.png" />
      </div>

      <v-btn text tile height="100%" class="nav-btn" to="/">Home</v-btn>
      <v-btn text tile height="100%" class="nav-btn" to="/news">News</v-btn>
      <v-btn text tile height="100%" class="nav-btn" to="/wiki">Wiki</v-btn>
      <v-btn text tile height="100%" class="nav-btn" href="https://store.steampowered.com">Steam</v-btn>
      <!--<v-btn text tile height="100%" class="nav-btn" :href="$config.discordUrl">Discord</v-btn>-->
      <v-btn text tile height="100%" class="nav-btn" to="/staff">Staff</v-btn>
      <v-btn text tile height="100%" class="nav-btn" to="/bans">Bans</v-btn>
      <v-btn text tile height="100%" class="nav-btn" to="/donate">Donate</v-btn>

      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            text
            tile
            height="100%"
            class="nav-btn"
            v-bind="attrs"
            v-on="on"
          >
            Prop Hunt
          </v-btn>
        </template>
        <v-list>
          <v-list-item to="/prophunt/rules">
            <v-list-item-title>Rules</v-list-item-title>
          </v-list-item>
          <v-list-item to="/prophunt/leaderboard">
            <v-list-item-title>Leaderboard</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-toolbar-items>

    <!-- Right item (pushed using ml-auto) -->
    <v-toolbar-items class="ml-auto d-flex align-center">
      <a class="steam-login" href="/auth/steam">
        <img class="steam-login-logo"src="/images/steam_login.png"/>
      </a>
    </v-toolbar-items>

  </v-app-bar>

</template>

<script>
  export default {
    data: () => ({
      items: [
        { title: 'Click Me' },
        { title: 'Click Me' },
        { title: 'Click Me' },
        { title: 'Click Me 2' },
      ],
    }),
  }
</script>

<style>
.v-toolbar__content {
  padding-top: 0 !important;
  padding-bottom: 0 !important;
}
.nav-btn {
  text-transform: none;
}
.nav-btn:hover {
  background-color: rgba(96, 96, 96, 0.5) !important;
}
.logo {
    min-width:200px;
    max-width:574px;
    width:21vw;
    height:auto;
    margin-right: 20px;
    display: block;
}

.steam-login {
  height: 50px;
  margin-top: 8px;
  margin-right: 20vw;
  display: block;
}

.steam-login-logo {
  height: 50px;
}


</style>