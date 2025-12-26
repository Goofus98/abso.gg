// plugins/vuex-persist.client.ts
import VuexPersistence from 'vuex-persist'

export default ({ store }) => {
  new VuexPersistence({
    key: 'gmod-store',
    storage: window.localStorage,
    //reducer: (state) => ({
      //gmodServers: {
        //gmServers: state.gmodServers.gmServers
      //}
   // })
  }).plugin(store)
}
