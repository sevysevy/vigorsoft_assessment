<template>
  <Layout>
    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center h-screen">
      <div class="text-center">
        <div class="relative inline-flex items-center justify-center mb-6">
          <div class="absolute animate-spin rounded-full h-20 w-20 border-t-4 border-b-4 border-blue-600"></div>
          <div class="absolute animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-indigo-400" style="animation-direction: reverse; animation-duration: 1s;"></div>
          <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
          </svg>
        </div>
        <p class="text-gray-600 font-semibold text-lg">Loading exchange data...</p>
        <p class="text-gray-400 text-sm mt-2">Connecting to market</p>
      </div>
    </div>

    <!-- Main Dashboard -->
    <div v-else class="space-y-8">
      <!-- Welcome Banner -->
      <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-2xl shadow-xl p-8 text-white">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h1 class="text-4xl font-bold mb-2 flex items-center gap-3">
              <span>Welcome back, {{ store.user?.name }}! 👋</span>
            </h1>
            <p class="text-blue-100 text-lg flex items-center gap-2">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
              </svg>
              Real-time limit order exchange
            </p>
          </div>
          <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
            <span class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse shadow-lg"></span>
            <span class="font-semibold">Market Online</span>
          </div>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- USD Balance Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-200">
          <div class="flex justify-between items-start mb-4">
            <div class="bg-green-100 p-3 rounded-lg">
              <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
              </svg>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">Available</span>
          </div>
          <div class="text-sm text-gray-600 font-medium mb-1">USD Balance</div>
          <div class="text-3xl font-bold text-gray-900 mb-2">
            ${{ store.usdBalance.toFixed(2) }}
          </div>
          <div class="flex items-center text-xs text-gray-500">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Ready to trade
          </div>
        </div>

        <!-- Active Orders Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-200">
          <div class="flex justify-between items-start mb-4">
            <div class="bg-blue-100 p-3 rounded-lg">
              <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
              </svg>
            </div>
            <span class="text-xs font-semibold text-blue-600 bg-blue-100 px-2 py-1 rounded-full">Live</span>
          </div>
          <div class="text-sm text-gray-600 font-medium mb-1">Active Orders</div>
          <div class="text-3xl font-bold text-gray-900 mb-2">
            {{ store.openOrders.length }}
          </div>
          <div class="flex items-center text-xs text-gray-500">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
            </svg>
            Pending execution
          </div>
        </div>

        <!-- Asset Balance Card -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-shadow duration-200">
          <div class="flex justify-between items-start mb-4">
            <div class="bg-purple-100 p-3 rounded-lg">
              <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
              </svg>
            </div>
            <span class="text-xs font-semibold text-purple-600 bg-purple-100 px-2 py-1 rounded-full">
              {{ store.selectedSymbol }}
            </span>
          </div>
          <div class="text-sm text-gray-600 font-medium mb-1">{{ store.selectedSymbol }} Available</div>
          <div class="text-3xl font-bold text-gray-900 mb-2">
            {{ store.assetBalance.toFixed(8) }}
          </div>
          <div class="flex items-center text-xs text-gray-500">
            <span class="mr-1">{{ store.selectedSymbol === 'BTC' ? '₿' : 'Ξ' }}</span>
            In your wallet
          </div>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
          <OrderForm />
          <OrderBook />
          
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
          <WalletOverview />
          <OrdersList />
        </div>
      </div>

      <TradeHistory />

      <!-- Footer Info Bar -->
      <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-500">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
          <div class="flex items-center space-x-6">
            <div class="flex items-center gap-2 text-sm">
              <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse shadow-lg"></span>
              <span class="font-semibold text-gray-700">Connected to real-time updates</span>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-sm text-gray-500">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
              </svg>
              <span>Lightning fast execution</span>
            </div>
          </div>
          <div class="flex items-center gap-3 text-xs text-gray-500">
            <span>Last sync: {{ new Date().toLocaleTimeString() }}</span>
            <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
            <span class="flex items-center gap-1">
              <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              All systems operational
            </span>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { useOrderStore } from '../stores/orderStore';

import Layout from '../components/Layout.vue';
import OrderForm from '../components/OrderForm.vue';
import OrderBook from '../components/OrderBook.vue';
import WalletOverview from '../components/WalletOverview.vue';
import OrdersList from '../components/OrdersList.vue';
import TradeHistory from '../components/TradeHistory.vue';

const store = useOrderStore();
const loading = ref(true);

// Auto-refresh interval
let refreshInterval: number;

onMounted(async () => {
  try {
    // Load initial data
    await Promise.all([
      store.fetchProfile(),
      store.fetchOrders(),
      store.fetchOrderbook()
    ]);
    
    // Set up auto-refresh every 10 seconds
    refreshInterval = window.setInterval(() => {
      store.fetchOrderbook();
      store.fetchOrders();
    }, 10000);
    
  } catch (error) {
    console.error('Failed to load dashboard data:', error);
  } finally {
    loading.value = false;
  }
});

onUnmounted(() => {
  // Clear interval
  if (refreshInterval) {
    clearInterval(refreshInterval);
  }
  
  // Cleanup Pusher
  store.cleanup();
});
</script>