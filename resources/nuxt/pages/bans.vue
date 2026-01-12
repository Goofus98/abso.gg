<template>
  <v-container fluid id="bans-container">
    <v-card-title>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
    </v-card-title>
    <v-data-table
      :headers="headers"
      :items="bans"
      :search="search"
      :server-items-length="totalItems"
      :page.sync="page"
      loading="true"
    >
      <template v-slot:footer.page-text>
            <v-pagination
              v-model="page"
              class="my-4"
              :length="length"
            ></v-pagination>
      </template>
    <template v-slot:item.carbs="{ item }">
      <div class="d-flex align-center">
        <v-avatar size="32" class="mr-2">
          <v-img src="/images/hourglass.png" />
        </v-avatar>

        <div>
          <div class="font-weight-medium">name</div>
          <div class="text--secondary text-caption">
            STEAMID
          </div>
        </div>
      </div>
    </template>
  </v-data-table>
  </v-container>
</template>

<script lang="ts">
import { Vue, Component, Watch } from "vue-property-decorator";
import { getModule } from "vuex-module-decorators";
import GModBansModule from "../store/gmodBans";

@Component({
  async fetch(this: Bans) {
    const GmodBansModule = getModule(GModBansModule, this.$store);
    const page = Number(this.$route.query.page) || 1;
    await GmodBansModule.gotoPage(page);
    this.length = GmodBansModule.length;
    this.page = page;
  },
})
export default class Bans extends Vue {
  page = 1;
  length = 1;
  search = '';
  headers = [
    {
      text: 'BanID',
      align: 'start',
      filterable: false,
      value: 'id',
    },
    { text: 'Date', value: 'created_at' },
    //{ text: 'Server', value: 'fat' },
    { text: 'Offender', value: 'SteamID' },
    { text: 'Admin', value: 'Admin' },
    { text: 'Length', value: 'ExpiryDate' },
    { text: 'Reason', value: 'Reason' },
    { text: 'Unban Reason', value: 'RevokeReason' }
  ];
  options = {
    page: 1,
    itemsPerPage: 10,
    sortBy: [],
    sortDesc: [],
  };

  mounted() {
    if (process.client) {
    }
  }
  @Watch('$route.query.page')
  async onPageQueryChanged(newPage: string | undefined) {
      const page = Number(newPage) || 1;
      if (page === this.page) return
      const GmodBansModule = getModule(GModBansModule, this.$store);
      await GmodBansModule.gotoPage(page);
      this.length = GmodBansModule.length;
      this.page = page;
  }
  @Watch('page')
  async fetchData(page: number) {
      const GmodBansModule = getModule(GModBansModule, this.$store);
      await GmodBansModule.gotoPage(page);
      this.length = GmodBansModule.length;
      this.$router.push({
        query: {
          ...this.$route.query,
          page: page.toString(),
        },
      });
  }

  get bans() {
    return getModule(GModBansModule, this.$store).bans;
  }

  get totalItems(){
    console.log(getModule(GModBansModule, this.$store).total);
    return getModule(GModBansModule, this.$store).total;
  }
}
</script>

<style>

#bans-container{
  padding-top: 32px;
  padding-bottom: 32px;
  padding-left: 12vw;
  padding-right: 12vw;
}
.v-data-footer__icons-before {
  display: none;
}
.v-data-footer__icons-after {
  display: none;
}
</style>