export default {
    ssr: true,
    srcDir: 'resources/nuxt/',

    head: {
        title: 'abso.gg GMod Servers',
        meta: [
            {charset: 'utf-8'},
            {name: 'viewport', content: 'width=device-width, initial-scale=1'},
            {hid: 'description', name: 'description', content: 'For people looking for the best GMod Servers'}
        ]/*,
        link: [
            {
                rel: "stylesheet",
                href: "https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900",
            },
            {
                rel: "stylesheet",
                href: "https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css",
            }
        ],*/
    },

    css: [],

    plugins: ["plugins/nuxt-axios-exporter.ts", "plugins/ably-echo.ts", { src: 'plugins/vuex-persist.ts', mode: 'client' }],

    components: true,

    buildModules: [
        "@nuxt/typescript-build",
        ['@nuxtjs/vuetify', { defaultAssets: true, treeShake: true }]
    ],

    vuetify: {
        theme: {
        dark: true
        }
    },

    modules: [ "@nuxtjs/axios"],

    build: {
        publicPath: process.env.NODE_ENV === 'production' ? 'assets/' : null,
        extractCSS: true,
    },

    generate: {
        dir: 'nuxt-public',
        exclude: [
            //'/bans'
            ///^\/bans/ // path starts with /admin
        ]
    },

    server: {
        port: 3000, // default: 3000
        host: '192.168.33.77' // default: localhost
    },

    watchers: {
        webpack: {
            aggregateTimeout: 300,
            poll: 500,
        },
    },
    publicRuntimeConfig: {
        discordUrl: process.env.DISCORD_URL,
        apiUrl: process.env.APP_URL,
        ablyKey: process.env.CLIENT_ABLY_KEY
    },
}