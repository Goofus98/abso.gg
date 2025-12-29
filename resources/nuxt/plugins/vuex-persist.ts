// plugins/vuex-persist.client.ts
import VuexPersistence from 'vuex-persist'

export default ({ store }) => {
  new VuexPersistence({
    key: 'vuex',
    storage: window.sessionStorage ,
    reducer: (state: any) => ({
      gmodServers: {
        gmServers: state.gmodServers.gmServers,
        isInitialized: state.gmodServers.isInitialized,
      },
      communityStats: {
        stats: state.communityStats.stats,
        isInitialized: state.communityStats.isInitialized,
      },
    })
  }).plugin(store)
}
//store.restored = vuexPersist.restoreState(store)