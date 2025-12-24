<template>

  <v-container fluid id="dash-container">
    <div class="carousel-wrapper">
        <v-carousel
        cycle
        height="12.5rem"
        hide-delimiters
        :show-arrows="false"
        >
        <v-carousel-item v-for="(item, i) in items" :key="i">
            <v-img
            :src="item.src"
            eager
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
                    <div class="stat-value">1,482,332</div>
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
                    <div class="stat-value">2159y 2mo 2w</div>
                    <div class="stat-label">TOTAL TIME PLAYED</div>
                    </div>
                </div>
                </v-card>
            </v-col>
            <!-- Discord -->
            <v-col cols="12" sm="6" md="4">
                <v-card class="server-card" tile>
                <v-img src="/images/discord.jpg" height="220">
                    <div class="server-overlay">
                    <div class="server-title">Discord</div>
                    <div class="server-footer">
                        <div class="server-left">
                        <span class="status-dot"></span>
                        <span>sups.gg/discord</span>
                        </div>
                        <div>2236/∞</div>
                    </div>
                    </div>
                </v-img>
                </v-card>
            </v-col>

            <v-col cols="12" sm="6" md="4" v-for="(item, i) in gmServers" :key="i">
                <v-card class="server-card" tile>
                <v-img src="/images/danktown.jpg" height="220">
                    <div class="server-overlay">
                    <div class="server-title">{{ item.name }}</div>
                    <div class="server-footer">
                        <div class="server-left">
                        <span class="status-dot"></span>
                        <span>{{ item.ip }}:{{ item.port }}</span>
                        </div>
                        <div>{{ item.online }}/{{ item.max_online }}</div>
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

@Component({
  // Nuxt fetch hook (SSR-safe)
  async fetch(this: Index) {
    const gmodServerModule = getModule(GModServersModule, this.$store);
    await gmodServerModule.initalize();
  }
})
export default class Index extends Vue {
  thing = 69;

  items = [
    { src: '/images/landing001.jpg' },
    { src: '/images/landing002.jpg' },
    { src: '/images/landing003.jpg' },
    { src: '/images/landing004.jpg' },
    { src: '/images/landing005.jpg' },
    { src: '/images/landing006.jpg' }
  ];

  // ✅ Getter only returns POJO state
  get gmServers() {
    return getModule(GModServersModule, this.$store).gmServers;
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

  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}

/* DARKEN */
.carousel-darken {
  position: absolute;
  inset: 0;
  z-index: 2;

  background: rgba(0, 0, 0, 0.55);
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
  background: radial-gradient(circle at 8px 5px, #000 3px, transparent 4px) repeat-x;
  background-size: 16px 10px;
  opacity: 0.6;
}

.stat-card::before { top: -10px; }
.stat-card::after { bottom: -10px; }

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

.server-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 12px;
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
