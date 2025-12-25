import { Plugin } from '@nuxt/types'
import Echo from '@ably/laravel-echo'
import * as Ably from 'ably'

let $echo: Echo;

const ablyEchoPlugin: Plugin = (context) => {
  $echo = new Echo({
    broadcaster: 'ably',
    //key: process.env.ABLY_PUBLIC_KEY,
    client: new Ably.Realtime({
      key: context.$config.ablyKey,
      //authUrl: '/api/broadcasting/auth',
    }),
  })

}
export { $echo }
export default ablyEchoPlugin