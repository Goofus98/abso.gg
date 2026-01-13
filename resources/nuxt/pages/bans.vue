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
    <v-text-field
      :value="search"
      @input="searchChanged"
      label="Search bans"
      clearable
    />
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
    <template v-slot:item.SteamID="{ item }">
      <div class="d-flex align-center">
        <v-avatar size="32" class="mr-2">
          <v-img src="/images/avatar.png" />
        </v-avatar>

        <div>
          <div class="font-weight-medium">{{ item.banned_user_name }}</div>
          <div class="text--secondary text-caption">
            {{ item.SteamID }}
          </div>
        </div>
      </div>
    </template>

    <template v-slot:item.ExpiryDate="{ item }">
      <div class="d-flex align-center">
        <v-avatar size="32" class="mr-2">
          <v-img src="/images/hourglass.png" />
        </v-avatar>

        <div>
          <div class="font-weight-medium">{{ formattedTime(item.ExpiryDate) }}</div>
        </div>
      </div>
    </template>
    <template v-slot:item.Admin="{ item }">
      <div class="d-flex align-center">
        <v-avatar size="32" class="mr-2">
          <v-img src="/images/console.png" />
        </v-avatar>

        <div>
          <div class="font-weight-medium">{{item.Admin}}</div>
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
import debounce from 'lodash/debounce';
import {sleep, sleepTrain, Killswitch} from "../core/Sleep";

@Component({
  async fetch(this: Bans) {
    const GmodBansModule = getModule(GModBansModule, this.$store);
    const page = Number(this.$route.query.page) || 1;
    const text = String(this.$route.query.search || "");
    await GmodBansModule.gotoPage({ page: page, search: text });
    this.length = GmodBansModule.length;
    this.page = page;
    this.search = text;
  },
})
export default class Bans extends Vue {
  page = 1;
  length = 1;
  search = '';
  searchChangeKillswitch!: Killswitch;

  created() {
    this.searchChangeKillswitch = new Killswitch();
  }
  headers = [
    {
      text: 'BanID',
      align: 'start',
      filterable: false,
      value: 'id',
      divider: true
    },
    { text: 'Date', value: 'created_at', divider: true },
    //{ text: 'Server', value: 'fat' },
    { text: 'Offender', value: 'SteamID', divider: true  },
    { text: 'Admin', value: 'Admin', divider: true  },
    { text: 'Length', value: 'ExpiryDate', divider: true  },
    { text: 'Reason', value: 'Reason', divider: true  },
    { text: 'Unban Reason', value: 'RevokeReason', divider: true  }
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

  async searchChanged(text: string) {
    this.searchChangeKillswitch.kill();
    await sleepTrain(async () => {
      await sleep(400, this.searchChangeKillswitch);
      const GmodBansModule = getModule(GModBansModule, this.$store);
      await GmodBansModule.gotoPage({ page: 1, search: text });
      this.length = GmodBansModule.length;
      this.page = 1;
      this.$router.push({
        query: {
          ...this.$route.query,
          page: this.page.toString(),
          search: text,
        },
      });
    });
  }

  @Watch('$route.query.page')
  async onPageQueryChanged(newPage: string | undefined) {
      const page = Number(newPage) || 1;
      if (page === this.page) return
      const GmodBansModule = getModule(GModBansModule, this.$store);
      await GmodBansModule.gotoPage({ page: page, search: this.search });
      this.length = GmodBansModule.length;
      this.page = page;
  }
  @Watch('page')
  async fetchData(page: number) {
      const GmodBansModule = getModule(GModBansModule, this.$store);
      await GmodBansModule.gotoPage({ page: page, search: this.search });
      this.length = GmodBansModule.length;
      this.$router.push({
        query: {
          ...this.$route.query,
          page: page.toString(),
        },
      });
  }
   /*onSearchInput(value: string) {
    this.search = value
    this.onSearchDebounced()
  }

  onSearchChanged() {
    this.$router.push({
      query: {
        ...this.$route.query,
        page: '1', // reset page
        search: this.search || undefined,
      },
    })
  }
 @Watch('$route.query', { deep: true })
  async onQueryChanged() {
    const page = Number(this.$route.query.page) || 1
    const search = this.$route.query.search || ''

    this.page = page
    this.search = search

    await this.fetchData()
  }

  async fetchData() {
      const GmodBansModule = getModule(GModBansModule, this.$store);
      await GmodBansModule.gotoPage(this.page, this.search);

  }*/
  formattedTime(timeInMins: number) {
    const units = [
      { label: 'y',  value: 60 * 24 * 365 },
      { label: 'mo', value: 60 * 24 * 30 },
      { label: 'w',  value: 60 * 24 * 7 },
      { label: 'd',  value: 60 * 24 },
      { label: 'h',  value: 60 },
      { label: 'm',  value: 1 },
    ];

    let remaining = Math.floor(timeInMins);
    const parts: string[] = [];

    for (const unit of units) {
      const amount = Math.floor(remaining / unit.value);
      if (amount > 0) {
        parts.push(`${amount}${unit.label}`);
        remaining -= amount * unit.value;
      }
    }

    return parts.length ? parts.join(' ') : '0m';
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