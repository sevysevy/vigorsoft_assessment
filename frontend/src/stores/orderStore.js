import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '../services/api';
import pusher from '../services/pusher';
import { parseDecimal, safeNumber } from '../utils/numberFormatter.js';

export const useOrderStore = defineStore('order', () => {

  const user = ref(null);
  const assets = ref([]);
  const orders = ref([]);
  const orderbook = ref({
    buy: [],
    sell: []
  });
  const selectedSymbol = ref('BTC');
  const loading = ref(false);
  
  // Add trade update tracker for Trade History component
  const lastTradeUpdate = ref(Date.now());

  const assetBalance = computed(() => {
    if (!assets.value || !Array.isArray(assets.value)) return 0
    
    const asset = assets.value.find(a => a.symbol === selectedSymbol.value)
    if (!asset || !asset.amount) return 0
    
    // Use helper function
    return parseDecimal(asset.amount);
  });

  const lockedAssetBalance = computed(() => {
    if (!assets.value || !Array.isArray(assets.value)) return 0
    
    const asset = assets.value.find(a => a.symbol === selectedSymbol.value)
    if (!asset || !asset.locked_amount) return 0
    
    return parseDecimal(asset.locked_amount);
  });

  const usdBalance = computed(() => {
    return user.value?.balance ? parseDecimal(user.value.balance) : 0
  });

  const openOrders = computed(() =>
    orders.value.filter(order => order.status === 'open')
  );

  const filledOrders = computed(() =>
    orders.value.filter(order => order.status === 'filled')
  );

  /* ======================
   * Actions
   * ====================== */
  async function fetchProfile() {
    try {
      const response = await api.get('/profile');
      user.value = response.data.user;
      assets.value = response.data.assets;
    } catch (error) {
      console.error('Failed to fetch profile:', error);
      throw error;
    }
  }

  async function fetchOrders() {
    try {
      const response = await api.get(`/orders?symbol=${selectedSymbol.value}`);
      orders.value = response.data;
    } catch (error) {
      console.error('Failed to fetch orders:', error);
      throw error;
    }
  }

  async function fetchOrderbook() {
    try {
      const response = await api.get(`/orders/orderbook?symbol=${selectedSymbol.value}`);
      orderbook.value = response.data;
    } catch (error) {
      console.error('Failed to fetch orderbook:', error);
      throw error;
    }
  }

  async function placeOrder(orderData) {
    loading.value = true;
    try {
      const response = await api.post('/orders', orderData);
      await Promise.all([
        fetchProfile(),
        fetchOrders(),
        fetchOrderbook()
      ]);
      
      notifyTradeUpdate();
      
      return response.data;
    } finally {
      loading.value = false;
    }
  }

  async function cancelOrder(orderId) {
    try {
      await api.post(`/orders/${orderId}/cancel`);
      await Promise.all([
        fetchProfile(),
        fetchOrders(),
        fetchOrderbook()
      ]);
    } catch (error) {
      console.error('Failed to cancel order:', error);
      throw error;
    }
  }

  function initRealTimeUpdates() {
    if (!user.value?.id) return;

    pusher.subscribeToChannel(
      `private-user.${user.value.id}`,
      'order.matched',
      (data) => {
        console.log(data)
        fetchProfile();
        fetchOrders();
        fetchOrderbook();
        
        
        notifyTradeUpdate();

        showNotification(
          'Order Matched',
          'Your order has been executed',
          'success'
        );
      }
    );
  }

  function setSymbol(symbol) {
    selectedSymbol.value = symbol;
    fetchOrders();
    fetchOrderbook();
  }

  function cleanup() {
    if (user.value?.id) {
      pusher.unsubscribeFromChannel(`private-user.${user.value.id}`);
    }
  }
  
  // Add method to notify trade updates
  function notifyTradeUpdate() {
    lastTradeUpdate.value = Date.now();
  }

  return {
    // State
    user,
    assets,
    orders,
    orderbook,
    selectedSymbol,
    loading,
    lastTradeUpdate, 

    // Getters
    usdBalance,
    assetBalance,
    lockedAssetBalance,
    openOrders,
    filledOrders,

    // Actions
    fetchProfile,
    fetchOrders,
    fetchOrderbook,
    placeOrder,
    cancelOrder,
    initRealTimeUpdates,
    setSymbol,
    cleanup,
    notifyTradeUpdate 
  };
});

function showNotification(title, message, type) {
  alert(`${title}: ${message}`);
}