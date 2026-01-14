<template>

  <v-container fluid id="dash-container" keep-alive>
    <div class="carousel-wrapper">
      <v-carousel
        cycle
        height="12.5rem"
        hide-delimiters
        :show-arrows="false"
      >
        <v-carousel-item
          v-for="(item, i) in items"
          :key="i"
        >
          <v-img
            :src="item.src"
            height="100%"
            position="center center"
          />
        </v-carousel-item>
      </v-carousel>

        <!-- Blur layer -->
        <div class="carousel-blur"></div>

        <!-- Dark overlay -->
        <div class="carousel-darken"></div>
        <!-- Overlay content -->
        <div class="carousel-overlay">
            <h1 class="title">Tinkering games one byte at a time.</h1>
            <p class="subtitle">We are a gaming community dedicated to research and development of sandbox experiences.</p>
        </div>
    </div>
    <v-container fill-height fluid>

        <!-- ===================== -->
        <!-- STAT CARDS (TOP) -->
        <!-- ===================== -->
        <v-row align="center" justify="center">
        <v-col cols="12" md="8" lg="6">
            <v-row>

            <v-col cols="12" sm="4" md="4">
                <v-card class="stat-card">
                <div class="stat-content">
                    <div class="stat-icon">
                    <v-img src="/images/entertainment.png" width="100" height="100" contain eager/>
                    </div>
                    <div class="stat-text">
                    <div class="stat-value">{{ onlinePlayers }}/{{ maxOnlinePlayers }}</div>
                    <div class="stat-label">PLAYERS ONLINE</div>
                    </div>
                </div>
                </v-card>
            </v-col>

            <v-col cols="12" sm="4" md="4">
                <v-card class="stat-card">
                <div class="stat-content">
                    <div class="stat-icon">
                    <v-img src="/images/popcorn.png" width="100" height="100" contain eager/>
                    </div>
                    <div class="stat-text">
                    <div class="stat-value">{{ playerCount }}</div>
                    <div class="stat-label">TOTAL PLAYERS</div>
                    </div>
                </div>
                </v-card>
            </v-col>

            <v-col cols="12" sm="4" md="4">
                <v-card class="stat-card">
                <div class="stat-content">
                    <div class="stat-icon">
                    <v-img src="/images/hourglass.png" width="100" height="100" contain eager/>
                    </div>
                    <div class="stat-text">
                    <div class="stat-value">{{ playTime }}</div>
                    <div class="stat-label">TOTAL TIME PLAYED</div>
                    </div>
                </div>
                </v-card>
            </v-col>
            <!-- Discord -->
            <v-col cols="12" sm="6" md="4">
                <v-card class="server-card" tile :href="$config.discordUrl">
                <v-img src="/images/discord.jpg" height="220">
                    <div class="server-overlay">
                    <div class="server-title">Discord</div>
                    <div class="server-footer">
                        <div class="server-left">
                        <span class="status-dot"></span>
                        <span>{{ discord }}</span>
                        </div>
                        <div>{{communityStats.discord_online_user_count}}/∞</div>
                    </div>
                    </div>
                </v-img>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="4" v-for="(item, i) in gmServers" :key="i">
                <v-card class="server-card" tile :href="`steam://connect/${item.ip}:${item.port}`">
                <v-img src="/images/danktown.jpg" height="220">
                    <div class="server-overlay">
                    <div class="server-title">{{ item.name }}</div>
                    <div class="server-bottom">
                      <div class="server-map">
                        {{ item.map }}
                      </div>
                      <div class="server-footer">
                          <div class="server-left">
                          <span class="status-dot"></span>
                          <span>{{ item.ip }}:{{ item.port }}</span>
                          </div>
                          <div>{{ item.online }}/{{ item.max_online }}</div>
                      </div>
                    </div>
                    </div>
                </v-img>
                </v-card>
            </v-col>

            </v-row>
        </v-col>
        </v-row>

    </v-container>
  </v-container>

</template>

<script lang="ts">
import { Vue, Component } from "vue-property-decorator";
import { getModule } from "vuex-module-decorators";
import GModServersModule from "../store/gmodServers";
import CommunityStatsModule from "../store/communityStats";

import Echo from '@ably/laravel-echo'
import * as Ably from 'ably'

@Component({
  async fetch(this: Index) {
    if (process.client) {
      await (this.$store as any).restored
    }
    const gmodServerModule = getModule(GModServersModule, this.$store);
    await gmodServerModule.initalize();

    const CommunStatsModule = getModule(CommunityStatsModule, this.$store);
    await CommunStatsModule.initalize();
  },
})
export default class Index extends Vue {
  thing = 69;

  items = [
    { src: '/images/landing001.jpg' },
    { src: '/images/landing002.jpg' },
    { src: '/images/landing003.jpg' },
    { src: '/images/landing004.jpg' },
    { src: '/images/landing005.jpg' },
    { src: '/images/landing006.jpg' },
    { src: '/images/landing007.jpg' }
  ];
  mounted() {
    if (process.client) {
        console.log("ready")
        const gmodServerModule = getModule(GModServersModule, this.$store);
        gmodServerModule.subscribe();

        const communityStatsModule = getModule(CommunityStatsModule, this.$store);
        communityStatsModule.subscribe();
    }
  }

  get discord(){
    return this.$config.discordUrl.replace(/^https?:\/\//, '');
  }
  // ✅ Getter only returns POJO state
  get gmServers() {
    return getModule(GModServersModule, this.$store).gmServers;
  }

  get communityStats() {
    return getModule(CommunityStatsModule, this.$store).stats;
  }

  get playerCount() {
    if (this.communityStats == null) {
      return "";
    }
    return new Intl.NumberFormat().format(this.communityStats.player_count)
  }

  get playTime() {
    if (this.communityStats == null) {
      return "";
    }
    let seconds = this.communityStats.play_time;
    const units = [
      { label: 'y',  value: 60 * 60 * 24 * 365 },
      { label: 'mo', value: 60 * 60 * 24 * 30 },
      { label: 'w',  value: 60 * 60 * 24 * 7 }
    ]

    let remaining = Math.floor(seconds)
    const parts: string[] = []

    for (const unit of units) {
      const amount = Math.floor(remaining / unit.value)
      if (amount > 0) {
        parts.push(`${amount}${unit.label}`)
        remaining -= amount * unit.value
      }
    }

    return parts.join(' ')
  }

  get maxOnlinePlayers() {
    let ret = 0;
    for (var v of this.gmServers) {
        ret = ret + v.max_online
    }
    return ret
  }
  get onlinePlayers() {
    let ret = 0;
    for (var v of this.gmServers) {
        ret = ret + v.online
    }
    return ret
  }
  // Example computed property
  get jank() {
    return this.gmServers.length ? this.gmServers : "";
  }
}
</script>

<style scoped>

#dash-container {
    padding: 0;
}

.carousel-wrapper {
  position: relative;
}
/* BLUR */
.carousel-blur {
  position: absolute;
  inset: 0;
  z-index: 1;

  backdrop-filter: blur(4px);
  -webkit-backdrop-filter: blur(4px);
}

/* DARKEN */
.carousel-darken {
  position: absolute;
  inset: 0;
  z-index: 2;

  background: rgba(0, 0, 0, 0.3);
}
.carousel-overlay {
  position: absolute;
  inset: 0;
  z-index: 2;

  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  text-align: center;
  color: white;

  pointer-events: none; /* allows carousel swipe/click */
}


/* ===================== */
/* STAT CARDS */
/* ===================== */

.stat-card {
  position: relative;
  overflow: visible;
  background: linear-gradient(to right, #5a0f14, #1a0a0a 45%, #0e0a0a);
  height: 90px;
  border-radius: 6px;
}

.stat-card::before,
.stat-card::after {
  content: "";
  position: absolute;
  left: 0;
  width: 100%;
  height: 10px;
  /*background: radial-gradient(circle at 8px 5px, #000 3px, transparent 4px) repeat-x;
  background-size: 16px 10px;*/
  opacity: 0.6;
}

/*.stat-card::before { top: -10px; }*/
/*.stat-card::after { bottom: -10px; }*/

.stat-content {
  display: flex;
  align-items: center;
  height: 100%;
}

.stat-icon {
  width: 90px;
  height: 100%;
  background: linear-gradient(to bottom, #ff1a1a, #b30000);
  display: flex;
  align-items: center;
  justify-content: center;
}

.stat-text {
  flex: 1;
  text-align: center;
}

.stat-value {
  font-size: 22px;
  font-weight: 600;
}

.stat-label {
  font-size: 13px;
  opacity: 0.8;
  letter-spacing: 1px;
}

/* ===================== */
/* SERVER CARDS */
/* ===================== */

.server-card {
  overflow: hidden;
  transition: transform 0.2s ease;
}

.server-card:hover {
  transform: translateY(-4px);
}

.server-overlay {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.85));
}

.server-title {
  background: rgba(0,0,0,0.8);
  text-align: center;
  padding: 8px;
  letter-spacing: 1px;
}

.server-bottom {
  margin-top: auto;
}

.server-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 12px;
  font-size: 13px;
}

.server-map {
  text-align: center;
  font-weight: 500;
  margin: 0;
  font-size: 13px;
}

.server-left {
  display: flex;
  align-items: center;
  gap: 8px;
}

.status-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #6bdc4d;
}
</style>
