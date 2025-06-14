console.log('Initializing Echo...');

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: 'pusher', // PENTING: gunakan 'pusher', bukan 'reverb'
  key: import.meta.env.VITE_REVERB_APP_KEY,
  cluster: 'mt1', // PENTING: HARUS ADA agar error hilang (pakai dummy saja)
  wsHost: import.meta.env.VITE_REVERB_HOST,
  wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
  wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
  forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
  enabledTransports: ['ws', 'wss'],
  disableStats: true // PENTING: agar pusher-js tidak hit endpoint stats
});
