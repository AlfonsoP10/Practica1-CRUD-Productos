import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

const echo = new Echo({
  broadcaster: 'reverb',
  key: '7hw7cymwdq6nxlxows08',
  wsHost: 'localhost',
  wsPort: 8080,
  wssPort: 8080,
  forceTLS: false,
  enabledTransports: ['ws'],
  authEndpoint: 'http://localhost:8000/api/v1/broadcasting/auth',
  auth: {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
      Accept: 'application/json',
    },
  },
})

export default echo