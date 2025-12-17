import Pusher from 'pusher-js';

class PusherService {
  constructor() {
    this.pusher = null;
    this.channels = new Map();
    this.initPusher();
  }

  initPusher() {
    const key = import.meta.env.VITE_PUSHER_APP_KEY;
    const cluster = import.meta.env.VITE_PUSHER_APP_CLUSTER;
    const apiUrl = import.meta.env.VITE_API_URL;
    const token = localStorage.getItem('token');

    if (!key) {
      console.warn('Pusher app key not found');
      return;
    }

    this.pusher = new Pusher(key, {
      cluster: cluster,
      forceTLS: true,
      authEndpoint: `${apiUrl}/broadcasting/auth`,
      auth: {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    });
  }

  subscribeToChannel(channelName, event, callback) {
    if (!this.pusher) {
      console.error('Pusher not initialized');
      return;
    }

    const channel = this.pusher.subscribe(channelName);
    channel.bind(event, callback);

    channel.bind('pusher:subscription_succeeded', () => {
      console.log('✓ Successfully subscribed to channel');
    });
    
    this.channels.set(channelName, channel);
  }

  unsubscribeFromChannel(channelName) {
    const channel = this.channels.get(channelName);

    if (channel) {
      channel.unbind_all();
      this.pusher.unsubscribe(channelName);
      this.channels.delete(channelName);
    }
  }

  disconnect() {
    if (this.pusher) {
      this.pusher.disconnect();
      this.pusher = null;
      this.channels.clear();
    }
  }
}

export default new PusherService();
