<template>
  <div class="card">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
        </svg>
        Trade History
      </h2>
      <div class="flex items-center gap-2">
        <span class="px-3 py-1.5 bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 text-xs font-bold rounded-full border border-blue-200">
          {{ trades.length }} {{ trades.length === 1 ? 'Trade' : 'Trades' }}
        </span>
      </div>
    </div>

    <!-- Filter Section -->
    <div class="mb-6 flex flex-wrap gap-4 items-center">
      <div class="flex-1 min-w-[200px]">
        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1.5">
          <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/>
          </svg>
          Filter by Symbol
        </label>
        <div class="relative">
          <select 
            v-model="selectedSymbol" 
            @change="fetchTrades" 
            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white font-semibold appearance-none cursor-pointer hover:border-gray-400"
          >
            <option value="">🌐 All Symbols</option>
            <option value="BTC">₿ Bitcoin (BTC)</option>
            <option value="ETH">Ξ Ethereum (ETH)</option>
          </select>
          <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="flex gap-3">
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-lg px-4 py-3">
          <div class="text-xs text-gray-600 font-medium">Total Volume</div>
          <div class="text-lg font-bold text-green-700">
            ${{ formatNumber(totalVolume, 2) }}
          </div>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-rose-50 border border-red-200 rounded-lg px-4 py-3">
          <div class="text-xs text-gray-600 font-medium">Total Fees</div>
          <div class="text-lg font-bold text-red-700">
            ${{ formatNumber(totalCommission, 2) }}
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-20">
      <div class="text-center">
        <div class="relative inline-flex items-center justify-center mb-4">
          <div class="absolute animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-600"></div>
          <div class="absolute animate-spin rounded-full h-12 w-12 border-t-4 border-b-4 border-indigo-400" style="animation-direction: reverse; animation-duration: 1s;"></div>
          <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
          </svg>
        </div>
        <p class="text-gray-600 font-semibold">Loading trades...</p>
      </div>
    </div>

    <!-- Trades Table -->
    <div v-else class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
          <tr>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
              <div class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                Time
              </div>
            </th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
              Symbol
            </th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
              Side
            </th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
              Price
            </th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
              Amount
            </th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
              Value
            </th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
              Commission
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr 
            v-for="trade in trades" 
            :key="trade.id" 
            class="hover:bg-blue-50 transition-colors duration-150"
          >
            <td class="px-4 py-4 whitespace-nowrap">
              <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm text-gray-600 font-medium">
                  {{ formatTime(trade.created_at) }}
                </span>
              </div>
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
              <div class="flex items-center gap-2">
                <span class="font-bold text-gray-900">{{ trade.symbol }}</span>
                <span class="text-xs text-gray-500">{{ trade.symbol === 'BTC' ? '₿' : 'Ξ' }}</span>
              </div>
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
              <span 
                :class="tradeSideClass(trade)" 
                class="px-3 py-1.5 text-xs font-bold rounded-full inline-flex items-center gap-1.5"
              >
                <span v-if="tradeSideText(trade) === 'BUY'">↑</span>
                <span v-else>↓</span>
                {{ tradeSideText(trade) }}
              </span>
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
              <span class="text-sm font-bold text-gray-900">${{ formatNumber(trade.price, 2) }}</span>
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
              <span class="text-sm font-medium text-gray-700">{{ formatNumber(trade.amount, 8) }}</span>
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
              <span class="text-sm font-bold text-blue-700">${{ formatNumber(trade.total_value, 2) }}</span>
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
              <div class="flex items-center gap-1">
                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                </svg>
                <span class="text-sm font-bold text-red-600">${{ formatNumber(trade.commission, 2) }}</span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Empty State -->
      <div v-if="trades.length === 0" class="text-center py-16 bg-gray-50">
        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-500 font-semibold text-lg mb-2">No trades yet</p>
        <p class="text-gray-400 text-sm">Your completed trades will appear here</p>
      </div>
    </div>

    <!-- Footer Stats -->
    <div v-if="!loading && trades.length > 0" class="mt-6 pt-6 border-t border-gray-200">
      <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-2 text-sm text-gray-500">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
          </svg>
          <span>Last updated: {{ new Date().toLocaleTimeString() }}</span>
        </div>
        <div class="flex items-center gap-4 text-sm">
          <div class="flex items-center gap-2">
            <span class="text-gray-500">Showing:</span>
            <span class="font-bold text-gray-900">{{ trades.length }} {{ trades.length === 1 ? 'trade' : 'trades' }}</span>
          </div>
          <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
          <div class="flex items-center gap-2">
            <span class="text-gray-500">Net P&L:</span>
            <span class="font-bold text-blue-700">${{ formatNumber(netProfitLoss, 2) }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch, onUnmounted } from 'vue';
import { useOrderStore } from '../stores/orderStore';
import api from '../services/api.js';

const store = useOrderStore();
const trades = ref([]);
const selectedSymbol = ref('');
const loading = ref(false);

// Computed properties with proper null checks
const totalVolume = computed(() => {
  if (!trades.value || trades.value.length === 0) return 0;
  return trades.value.reduce((sum, trade) => {
    const value = Number(trade.total_value) || 0;
    return sum + value;
  }, 0);
});

const totalCommission = computed(() => {
  if (!trades.value || trades.value.length === 0) return 0;
  return trades.value.reduce((sum, trade) => {
    const commission = Number(trade.commission) || 0;
    return sum + commission;
  }, 0);
});

const netProfitLoss = computed(() => {
  return totalVolume.value - totalCommission.value;
});

// Fetch trades
async function fetchTrades() {
  loading.value = true;
  try {
    let url = '/trades';
    if (selectedSymbol.value) {
      url += `?symbol=${selectedSymbol.value}`;
    }
    
    const response = await api.get(url);
    trades.value = response.data.data || response.data || [];
  } catch (error) {
    console.error('Failed to fetch trades:', error);
    trades.value = [];
  } finally {
    loading.value = false;
  }
}

// Helper functions
const formatTime = (timestamp: string) => {
  if (!timestamp) return 'N/A';
  const date = new Date(timestamp);
  return date.toLocaleTimeString([], { 
    hour: '2-digit', 
    minute: '2-digit',
    hour12: true 
  });
};

const formatNumber = (value: any, decimals: number) => {
  const num = Number(value);
  if (isNaN(num)) return '0.' + '0'.repeat(decimals);
  return num.toFixed(decimals);
};

// Get user from store or localStorage
const getUserId = () => {
  try {
    const userStr = localStorage.getItem('user');
    if (userStr) {
      const user = JSON.parse(userStr);
      return user?.id || null;
    }
    return store.user?.id || null;
  } catch (error) {
    console.error('Error getting user:', error);
    return null;
  }
};

const tradeSideClass = (trade: any) => {
  const userId = getUserId();
  return trade.buyer_id === userId 
    ? 'bg-green-100 text-green-800' 
    : 'bg-red-100 text-red-800';
};

const tradeSideText = (trade: any) => {
  const userId = getUserId();
  return trade.buyer_id === userId ? 'BUY' : 'SELL';
};

// Watch for store changes to refresh trades immediately
watch(() => store.lastTradeUpdate, () => {
  fetchTrades();
});

// Auto-refresh trades every 10 seconds
let refreshInterval: ReturnType<typeof setInterval> | null = null;

onMounted(() => {
  fetchTrades();
  
  // Set up auto-refresh
  refreshInterval = setInterval(() => {
    fetchTrades();
  }, 10000); // Refresh every 10 seconds
});

// Cleanup interval on unmount
onUnmounted(() => {
  if (refreshInterval) {
    clearInterval(refreshInterval);
  }
});
</script>