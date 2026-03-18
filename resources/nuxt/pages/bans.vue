<template>
  <v-container fluid id="bans-container">
    <v-card-title>
      <v-text-field :value="search" @input="searchChanged" label="Search bans" append-icon="mdi-magnify" single-line
        hide-details clearable />
    </v-card-title>
    <v-data-table :headers="headers" :items="bans" :search="search" :server-items-length="totalItems" :page.sync="page"
      :mobile-breakpoint="0" :items-per-page.sync="itemsPerPage" :footer-props="{ 'items-per-page-options': opts }">
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
        <div class="reason-cell">
          <!-- Overlay label -->
          <v-chip x-small v-if="item.ExpiryDateEdited" @click="viewExipryHistory(item.id)">
            Edited
          </v-chip>
          <div class="d-flex align-center justify-space-between">
            <span class="reason-text">
              {{ formattedTime(item.ExpiryDate) }}
            </span>
            <v-menu offset-y>
              <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on">
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </template>

              <v-list dense>

                <v-list-item @click="editItem(item.id, bans.indexOf(item)); isEditingExpiryDate = true">
                  <v-list-item-title>Edit</v-list-item-title>
                </v-list-item>

              </v-list>
            </v-menu>
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
        <div class="reason-cell">
          <!-- Overlay label -->
          <v-chip x-small v-if="item.ReasonEdited" @click="viewReasonHistory(item.id)">
            Edited
          </v-chip>

          <!-- Normal cell content -->
          <div class="d-flex align-center justify-space-between">
            <span class="reason-text">
              {{ item.Reason }}
            </span>

            <v-menu offset-y>
              <template v-slot:activator="{ on, attrs }">
                <v-btn icon v-bind="attrs" v-on="on">
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </template>

              <v-list dense>

                <v-list-item @click="editItem(item.id, bans.indexOf(item)); isEditingReason = true">
                  <v-list-item-title>Edit</v-list-item-title>
                </v-list-item>

              </v-list>
            </v-menu>
          </div>
        </div>
      </template>


    </v-data-table>

    <v-dialog v-model="isEditingExpiryDate" persistent max-width="700px" :retain-focus="false">
      <v-card>
        <v-card-title>
          <span class="text-h5">Edit Ban Length (ID: {{ EditingBanID }})</span>
        </v-card-title>
        <v-card-subtitle>{{banLengthHelperText}}</v-card-subtitle>
        <v-card-text>
          <v-container>

        <v-row align="center" justify="center">
            <v-col cols="12" sm="6" md="2">
              <h5>Years</h5>
              <v-text-field
                v-model.number="editingExpiryDateYears"
                density="compact"
                style="width: 120px"
                type="number"
                outlined="true"
                oninput="if(this.value < 0) this.value = 0;"
                hide-details
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="2">
              <h5>Months</h5>
              <v-text-field
                v-model.number="editingExpiryDateMonths"
                density="compact"
                style="width: 120px"
                type="number"
                outlined="true"
                oninput="if(this.value < 0) this.value = 0;"
                hide-details
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="2">
              <h5>Weeks</h5>
              <v-text-field
                v-model.number="editingExpiryDateWeeks"
                density="compact"
                style="width: 120px"
                type="number"
                outlined="true"
                oninput="if(this.value < 0) this.value = 0;"
                hide-details
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="2">
              <h5>Days</h5>
              <v-text-field
                v-model.number="editingExpiryDateDays"
                density="compact"
                style="width: 120px"
                type="number"
                outlined="true"
                oninput="if(this.value < 0) this.value = 0;"
                hide-details
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="2">
              <h5>Hours</h5>
              <v-text-field
                v-model.number="editingExpiryDateHours"
                density="compact"
                style="width: 120px"
                type="number"
                outlined="true"
                oninput="if(this.value < 0) this.value = 0;"
                hide-details
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="6" md="2">
              <h5>Mins</h5>
              <v-text-field
                v-model.number="editingExpiryDateMins"
                density="compact"
                style="width: 120px"
                type="number"
                outlined="true"
                oninput="if(this.value < 0) this.value = 0;"
                hide-details
              ></v-text-field>
            </v-col>
          </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="banLengthClose">
            Close
          </v-btn>
          <v-btn text @click="banLengthSave">
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="isEditingReason" persistent max-width="600px" :retain-focus="false">
      <v-card>
        <v-card-title>
          <span class="text-h5">Edit Ban Reason (ID: {{ EditingBanID }})</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-textarea counter label="Reason" :rules="[v => v.length <= 255 || 'Max 255 characters']"
              :value="editingBan?.Reason ?? ''" @input="bannedReasonChange"></v-textarea>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn text @click="bannedReasonClose">
            Close
          </v-btn>
          <v-btn text @click="bannedReasonSave">
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="isViewingReasonHistory" persistent max-width="600px" :retain-focus="false">
      <v-card>
        <v-card-title>
          <span class="text-h5">Ban Reason History (ID: {{ ViewingBanID }})</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-simple-table>
              <template v-slot:default>
                <thead>
                  <tr>
                    <th class="text-left">Admin</th>
                    <th class="text-left">Date</th>
                    <th class="text-left">New Reason</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="audit in audits" :key="audit.id" v-if="audit.new_values?.Reason">
                    <td>
                      <div v-if=!audit.user_id class="d-flex align-center">
                        <v-avatar size="32" class="mr-2">
                          <v-img src="/images/console.png" />
                        </v-avatar>

                        <div>
                          <div class="font-weight-medium">CONSOLE</div>
                        </div>
                      </div>

                      <div v-if=audit.user_id class="d-flex align-center">
                        <Avatar :avatar-href="audit.admin_user_avatar" :frame-href="audit.admin_user_avatar_frame" size="42" />
                        <div>
                          <div class="font-weight-medium">{{ audit.user_name }}</div>
                          <div class="text--secondary text-caption">
                            {{ audit.user_id }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      {{ audit.formatted_created_at }}
                    </td>
                    <td class="reason-table-cell">
                      {{ audit.new_values.Reason }}
                    </td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer />
          <v-btn text @click="isViewingReasonHistory = false">
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

      <v-dialog v-model="isViewingExpiryHistory" persistent max-width="600px" :retain-focus="false">
      <v-card>
        <v-card-title>
          <span class="text-h5">Ban Expiry History (ID: {{ ViewingBanID }})</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-simple-table>
              <template v-slot:default>
                <thead>
                  <tr>
                    <th class="text-left">Admin</th>
                    <th class="text-left">Date</th>
                    <th class="text-left">New Time</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="audit in audits" :key="audit.id" v-if="audit.new_values?.ExpiryDate">
                    <td>
                      <div v-if=!audit.user_id class="d-flex align-center">
                        <v-avatar size="32" class="mr-2">
                          <v-img src="/images/console.png" />
                        </v-avatar>

                        <div>
                          <div class="font-weight-medium">CONSOLE</div>
                        </div>
                      </div>

                      <div v-if=audit.user_id class="d-flex align-center">
                        <Avatar :avatar-href="audit.admin_user_avatar" :frame-href="audit.admin_user_avatar_frame" size="42" />
                        <div>
                          <div class="font-weight-medium">{{ audit.user_name }}</div>
                          <div class="text--secondary text-caption">
                            {{ audit.user_id }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td>
                      {{ audit.formatted_created_at }}
                    </td>
                    <td class="reason-table-cell">
                      {{ formattedTime(audit.new_values.ExpiryDate) }}
                    </td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer />
          <v-btn text @click="isViewingExpiryHistory = false">
            Close
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-snackbar v-model="success" color="success" timeout="3000" top>
      Saved successfully!
    </v-snackbar>
  </v-container>
</template>

<script lang="ts">
import { Vue, Component, Watch } from "vue-property-decorator";
import { getModule } from "vuex-module-decorators";
import GModBansModule from "../store/gmodBans";
import gmodBansAuditsModule from "../store/gmodBansAudits";
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
  isEditingReason = false;
  isEditingExpiryDate = false;
  isViewingReasonHistory = false;
  isViewingExpiryHistory = false;
  success = false;
  EditingBanID = 1;
  EditingBanIndex = 0;
  ViewingBanID = 1;
  
  shouldAdd = false;
  editingExpiryDateYears = 0;
  editingExpiryDateMonths = 0;
  editingExpiryDateWeeks = 0;
  editingExpiryDateDays = 0;
  editingExpiryDateHours = 0;
  editingExpiryDateMins = 0;

  banLengthHelperText = "All 0 = Permanent Ban."
  headers = [
    {
      text: 'BanID',
      align: 'start',
      filterable: false,
      value: 'id',
      divider: true
    },
    { text: 'Date', value: 'formatted_created_at', divider: true },
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
  async created() {
    this.searchChangeKillswitch = new Killswitch();
  }

  mounted() {
    if (process.client) {
    }
  }

  editItem(id: number, index: number) {
    this.EditingBanID = id;
    this.EditingBanIndex = index;
  }

  bannedReasonChange(text: string) {
    this.bannedReason = text;
  }

  @Watch('editingExpiryDateYears')
  @Watch('editingExpiryDateMonths')
  @Watch('editingExpiryDateDays')
  @Watch('editingExpiryDateWeeks')
  @Watch('editingExpiryDateHours')
  @Watch('editingExpiryDateMins')
  onEditingExpiryDateYearsChange(val: number) {
    this.updateBanLengthHelper();
  }

  updateBanLengthHelper() {
    const delta = this.expiryDelta;
    if (delta == 0) {
      this.banLengthHelperText = "All 0 = Permanent Ban.";
      return;
    }
    const date = new Date(this.editingBan.created_at);
    date.setMinutes(date.getMinutes() + delta);
    if (isNaN(date.getTime())) {
      this.banLengthHelperText = 'Invalid date';
      return;
    }
    this.banLengthHelperText = this.editingBan.banned_user_name + " will be unbanned on: " + new Intl.DateTimeFormat('en-US', {
      month: 'short',
      day: 'numeric',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      hour12: false,
    }).format(date);

    if (delta == 2147483647) {
      this.banLengthHelperText = this.banLengthHelperText + " (max ban length)"
    }
  }

  async viewReasonHistory(id: number) {
    const GmodBansAuditsModule = getModule(gmodBansAuditsModule, this.$store);
    await GmodBansAuditsModule.getAudits({ banID: id });

    this.isViewingReasonHistory = true;
    this.ViewingBanID = id;
  }

  async viewExipryHistory(id: number) {
    const GmodBansAuditsModule = getModule(gmodBansAuditsModule, this.$store);
    await GmodBansAuditsModule.getAudits({ banID: id });

    this.isViewingExpiryHistory = true;
    this.ViewingBanID = id;
  }

  get audits() {
    const GmodBansAuditsModule = getModule(gmodBansAuditsModule, this.$store);
    return GmodBansAuditsModule.audits;
  }

  get expiryDelta() {
    return Math.min((this.editingExpiryDateYears * 525600)+ (this.editingExpiryDateMonths * 43800) + (this.editingExpiryDateWeeks * 10080) + (this.editingExpiryDateDays * 1440) + (this.editingExpiryDateHours * 60) + this.editingExpiryDateMins, 2147483647);
  }

  banLengthClose() {
    this.editingExpiryDateYears = 0;
    this.editingExpiryDateMonths = 0;
    this.editingExpiryDateWeeks = 0;
    this.editingExpiryDateDays = 0;
    this.editingExpiryDateHours = 0;
    this.editingExpiryDateMins = 0;
    this.isEditingExpiryDate = false;
  }
  async banLengthSave() {
    const GmodBansModule = getModule(GModBansModule, this.$store);

    this.success = await GmodBansModule.updateBan({
      banID: this.EditingBanID,
      ExpiryDate: this.expiryDelta
    });
    this.editingExpiryDateYears = 0;
    this.editingExpiryDateMonths = 0;
    this.editingExpiryDateWeeks = 0;
    this.editingExpiryDateDays = 0;
    this.editingExpiryDateHours = 0;
    this.editingExpiryDateMins = 0;
    this.isEditingExpiryDate = false;
  }

  bannedReasonClose() {
    this.bannedReason = '';
    this.isEditingReason = false;
  }
  async bannedReasonSave() {
    if (this.bannedReason == '') {
      return;
    }
    const GmodBansModule = getModule(GModBansModule, this.$store);

    this.success = await GmodBansModule.updateBan({
      banID: this.EditingBanID,
      Reason: this.bannedReason
    });

    this.bannedReason = '';
    this.isEditingReason = false;
  }

  async searchChanged(text: string) {
    this.searchChangeKillswitch.kill();
    await sleepTrain(async () => {
      await sleep(500, this.searchChangeKillswitch);
      this.page = 1;
      this.$router.push({
        query: {
          ...this.$route.query,
          page: this.page.toString(),
          //items: this.itemsPerPage.toString(),
          search: text,
        },
      });
    });
  }
  @Watch('itemsPerPage')
  onItemsPerPageChange(itemsPerPage: number) {
    this.$router.push({
      query: {
        ...this.$route.query,
        items: itemsPerPage.toString(),
      },
    });
  }
  @Watch('page')
  onPageChange(page: number) {
    this.$router.push({
      query: {
        ...this.$route.query,
        page: page.toString(),
      },
    });
  }

  @Watch('$route.query', { immediate: true, deep: true })
  async onQueryChanged() {
    const page = Number(this.$route.query.page) || 1;
    const items = Number(this.$route.query.items) || 25;
    const text = String(this.$route.query.search || "");
    this.itemsPerPage = items;
    this.page = page;
    await this.fetchData({
      page,
      itemsPerPage: items,
      search: text,
    });
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
  get editingBan() {
    return this.bans[this.EditingBanIndex];
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
  background-color: rgba(0, 0, 0, 0);
}

.v-data-footer__icons-before {
  display: none;
}

.v-data-footer__icons-after {
  display: none;
}

.reason-cell {
  position: relative;
}

.edited-label {
  position: absolute;
  top: -6px;
  left: 0;
  font-size: 10px;
  font-weight: 600;
  color: #868686;
  pointer-events: none;
  z-index: 1;
}

.reason-table-cell {
  white-space: normal !important;
  word-break: break-word;
}
</style>