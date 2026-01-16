<template>
  <v-container fluid id="bans-container">
    <v-card-title>
      <v-text-field :value="search" @input="searchChanged" label="Search bans" append-icon="mdi-magnify" single-line
        hide-details clearable />
    </v-card-title>
    <v-data-table :headers="headers" :items="bans" :search="search" :server-items-length="totalItems" :page.sync="page"
      :items-per-page.sync="itemsPerPage" :footer-props="{ 'items-per-page-options': opts }">
      <template v-slot:footer.page-text>
        <v-pagination v-model="page" class="my-4" :length="length"></v-pagination>
      </template>
      <template v-slot:item.SteamID="{ item }">
        <div class="d-flex align-center">
          <Avatar :avatar-href="item.banned_user_avatar" :frame-href="item.banned_user_avatar_frame" size="42" />
          <!--  <v-avatar size="32" class="mr-2">

          <v-img :src="item.banned_user_avatar" />
        </v-avatar>-->

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
            <div class="font-weight-medium">{{ item.Admin }}</div>
          </div>
        </div>
      </template>
      <template v-slot:item.Reason="{ item }">
        <div class="d-flex align-center justify-space-between">
          <div>
            <div class="font-weight-medium">{{ item.Reason }}</div>
          </div>

          <v-menu offset-y>
            <template v-slot:activator="{ on, attrs }">
              <v-btn icon v-bind="attrs" v-on="on">
                <v-icon>mdi-dots-vertical</v-icon>
              </v-btn>
            </template>
            <v-list dense>

              <v-dialog v-model="dialog" persistent max-width="600px">
                <template v-slot:activator="{ on, attrs }">

                  <v-list-item v-bind="attrs" v-on="on" @click="editItem(item.id)">
                    <v-list-item-title>Edit</v-list-item-title>
                  </v-list-item>
                </template>
                <v-card>
                  <v-card-title>
                    <span class="text-h5">Edit Ban Reason</span>
                  </v-card-title>
                  <v-card-text>
                    <v-container>
                      <v-textarea counter label="Reason" :rules="[v => v.length <= 255 || 'Max 255 characters']"
                        :value="item.Reason" @input="bannedReasonChange"></v-textarea>
                    </v-container>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text @click="dialog = false">
                      Close
                    </v-btn>
                    <v-btn text @click="bannedReasonSave">
                      Save
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>

            </v-list>
          </v-menu>
        </div>
      </template>

    </v-data-table>

    <v-snackbar
    v-model="success"
    color="success"
    timeout="3000"
    top
  >
    Saved successfully!
  </v-snackbar>
  </v-container>


</template>

<script lang="ts">
import { Vue, Component, Watch } from "vue-property-decorator";
import { getModule } from "vuex-module-decorators";
import GModBansModule from "../store/gmodBans";
import debounce from 'lodash/debounce';
import { sleep, sleepTrain, Killswitch } from "../core/Sleep";

@Component({
  async fetch(this: Bans) {
    const GmodBansModule = getModule(GModBansModule, this.$store);
    const page = Number(this.$route.query.page) || 1;
    const items = Number(this.$route.query.items) || 25;
    const text = String(this.$route.query.search || "");
    await GmodBansModule.gotoPage({ page: page, items: items, search: text });
    this.length = GmodBansModule.length;
    this.page = page;
    this.search = text;
    this.itemsPerPage = items;
  },
})
export default class Bans extends Vue {
  page = 1;
  length = 1;
  itemsPerPage = 25;
  opts = [10, 25, 50, 100];
  search = '';
  bannedReason = '';
  searchChangeKillswitch!: Killswitch;
  ignoreWatchers = false;
  dialog = false;
  success = false;
  EditingBanID = 1;
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
    { text: 'Offender', value: 'SteamID', divider: true },
    { text: 'Admin', value: 'Admin', divider: true },
    { text: 'Length', value: 'ExpiryDate', divider: true },
    { text: 'Reason', value: 'Reason', divider: true },
    { text: 'Unban Reason', value: 'RevokeReason', divider: true }
  ];


  options = {
    page: 1,
    itemsPerPage: 10,
    sortBy: [],
    sortDesc: [],
  };
  created() {
    this.searchChangeKillswitch = new Killswitch();
  }
  mounted() {
    if (process.client) {
    }
  }

  editItem(id: number) {
    this.EditingBanID = id;
  }

  bannedReasonChange(text: string) {
    this.bannedReason = text;
  }

  async bannedReasonSave(){
    if (this.bannedReason == '') {
      return;
    }
    const GmodBansModule = getModule(GModBansModule, this.$store);

    this.success = await GmodBansModule.updateBan({
      banID: this.EditingBanID,
      Reason: this.bannedReason
    });

    this.bannedReason = '';
    this.dialog = false;

  }

  async searchChanged(text: string) {
    this.searchChangeKillswitch.kill();
    await sleepTrain(async () => {
      await sleep(500, this.searchChangeKillswitch);
      await this.fetchData({ search: text, page: 1 });
      this.ignoreWatchers = true;
      this.page = 1;
      this.$router.push({
        query: {
          ...this.$route.query,
          page: this.page.toString(),
          //items: this.itemsPerPage.toString(),
          search: text,
        },
      });
      this.ignoreWatchers = false;
    });
  }
  @Watch('itemsPerPage')
  async onItemsPerPageChange(itemsPerPage: number) {

    await this.fetchData({ itemsPerPage: itemsPerPage });
    this.ignoreWatchers = true;
    this.$router.push({
      query: {
        ...this.$route.query,
        items: itemsPerPage.toString(),
      },
    });
    this.ignoreWatchers = false;
  }
  @Watch('page')
  async onPageChange(page: number) {
    await this.fetchData({ page: page });
    this.ignoreWatchers = true;
    this.$router.push({
      query: {
        ...this.$route.query,
        page: page.toString(),
      },
    });
    this.ignoreWatchers = false;
  }

  @Watch('$route.query')
  async onQueryChanged() {
    if (this.ignoreWatchers) return;
    const page = Number(this.$route.query.page) || 1;
    const items = Number(this.$route.query.items) || 25;
    const text = String(this.$route.query.search || "");
    await this.fetchData({ page: page, itemsPerPage: items, search: text });
  }
  async fetchData(options: {
    page?: number;
    itemsPerPage?: number;
    search?: string;
  } = {}) {
    const GmodBansModule = getModule(GModBansModule, this.$store);

    await GmodBansModule.gotoPage({
      page: options.page ?? this.page,
      items: options.itemsPerPage ?? this.itemsPerPage,
      search: options.search ?? this.search,
    });

    this.length = GmodBansModule.length;
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
      { label: 'y', value: 60 * 24 * 365 },
      { label: 'mo', value: 60 * 24 * 30 },
      { label: 'w', value: 60 * 24 * 7 },
      { label: 'd', value: 60 * 24 },
      { label: 'h', value: 60 },
      { label: 'm', value: 1 },
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

  get totalItems() {
    console.log(getModule(GModBansModule, this.$store).total);
    return getModule(GModBansModule, this.$store).total;
  }
}
</script>

<style>
#bans-container {
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