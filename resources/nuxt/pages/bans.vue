<template>
  <v-container>
    <v-row>
      <v-col
        v-for="item in items"
        :key="item.id"
        cols="12"
        sm="6"
        md="4"
        lg="3"
      >
        <v-card class="shop-card" outlined>
          <v-img
            :src="item.image"
            height="180"
            eager
            transition="false"
          />

          <v-card-title class="text-subtitle-1">
            {{ item.name }}
          </v-card-title>

          <v-card-subtitle class="grey--text">
            {{ item.description }}
          </v-card-subtitle>

          <v-card-text class="font-weight-bold">
            ${{ item.price.toFixed(2) }}
          </v-card-text>

          <v-card-actions>
            <v-btn color="primary" block>
              Buy
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>

export default {
  data () {
    return {
      items: [
        {
          id: 1,
          name: 'Starter Sword',
          description: 'Basic weapon for beginners',
          price: 9.99,
          image: 'https://picsum.photos/400/300?1'
        },
        {
          id: 2,
          name: 'Health Potion',
          description: 'Restores 50 HP',
          price: 4.99,
          image: 'https://picsum.photos/400/300?2'
        },
        {
          id: 3,
          name: 'Magic Armor',
          description: 'Boosts defense stats',
          price: 29.99,
          image: 'https://picsum.photos/400/300?3'
        },
        {
          id: 4,
          name: 'Speed Boots',
          description: 'Run faster',
          price: 14.99,
          image: 'https://picsum.photos/400/300?4'
        }
      ]
    }
  }
}

</script>

<style>
.shop-card {
  transition: transform 0.2s ease;
}

.shop-card:hover {
  transform: translateY(-4px);
}

</style>