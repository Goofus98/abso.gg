<template>
    <v-app>
        <v-card class="ma-4">
            <v-expansion-panels>
                <v-expansion-panel
                v-for="(item,i) in 5"
                :key="i"
                >
                <v-expansion-panel-header>
                    Item
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </v-card>
        {{ jank }}
    </v-app>

</template>

<script lang="ts">
import {Vue, Component} from "vue-property-decorator";
import {getModule} from "vuex-module-decorators";
import JankModule from "../store/jank";

@Component
export default class IndexClass extends Vue {
    thing = 69;
    jankmodule: JankModule|null = null;

    async created() {
        
        const data = await this.$axios.$get("http://abso.gg/api/areas");
        console.log(data);

        this.jankmodule = getModule(JankModule, this.$store);
        await this.jankmodule.goPP();
    }

    get jank(){
        if (this.jankmodule == null) {
            return "";
        }
        return this.jankmodule.variable;
    }
}
</script>

<style></style>