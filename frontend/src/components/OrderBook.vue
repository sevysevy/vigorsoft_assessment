<template>
  <div class="card">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
          <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        Order Book
      </h2>
      <div class="flex items-center gap-3">
        <span class="px-3 py-1.5 bg-blue-100 text-blue-800 text-sm font-bold rounded-full">
          {{ store.selectedSymbol }}
        </span>
        <div class="flex items-center gap-2">
          <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
          <span class="text-xs text-gray-500 font-medium">Live</span>
        </div>
      </div>
    </div>

    <!-- Order Book Grid -->
    <div class="grid grid-cols-2 gap-6">
      <!-- Sell Orders (Asks) -->
      <div class="bg-gradient-to-br from-red-50 to-rose-50 border border-red-200 rounded-lg p-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="font-bold text-red-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
            Sell Orders (Asks)
          </h3>
          <span class="text-xs text-gray-600 font-medium bg-white px-2 py-1 rounded">
            {{ formattedSellOrders.length }}
          </span>
        </div>
        
        <div class="bg-white rounded-lg mb-2 px-3 py-2 flex justify-between text-xs font-semibold text-gray-600">
          <span>Price (USD)</span>
          <span>Amount</span>
        </div>
        
        <div class="space-y-1.5 max-h-64 overflow-y-auto pr-1 custom-scrollbar">
          <div
            v-for="(order, index) in formattedSellOrders"
            :key="`sell-${index}`"
            class="relative bg-white p-3 rounded-lg hover:shadow-md transition-all duration-200 cursor-pointer border border-transparent hover:border-red-300"
          >
            <div class="flex justify-between items-center">
              <span class="font-bold text-red-600 text-base">
                ${{ formatDecimal(order.price, 2) }}
              </span>
              <span class="text-gray-700 font-medium">
                {{ formatDecimal(order.amount, 8) }}
              </span>
            </div>
            <!-- Depth visualization -->
            <div class="absolute inset-0 bg-red-100 rounded-lg opacity-20" 
                 :style="{ width: `${(order.amount / Math.max(...formattedSellOrders.map(o => o.amount))) * 100}%` }">
            </div>
          </div>
          
          <div v-if="formattedSellOrders.length === 0" class="text-center py-8">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-gray-400 text-sm">No sell orders</p>
          </div>
        </div>
        
        <!-- Sell Total -->
        <div v-if="totalSell > 0" class="mt-4 pt-4 border-t border-red-200 bg-white rounded-lg p-3">
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 font-medium">Total Sell Volume:</span>
            <span class="font-bold text-red-700">{{ formatDecimal(totalSell, 8) }}</span>
          </div>
        </div>
      </div>

      <!-- Buy Orders (Bids) -->
      <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="font-bold text-green-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            Buy Orders (Bids)
          </h3>
          <span class="text-xs text-gray-600 font-medium bg-white px-2 py-1 rounded">
            {{ formattedBuyOrders.length }}
          </span>
        </div>
        
        <div class="bg-white rounded-lg mb-2 px-3 py-2 flex justify-between text-xs font-semibold text-gray-600">
          <span>Price (USD)</span>
          <span>Amount</span>
        </div>
        
        <div class="space-y-1.5 max-h-64 overflow-y-auto pr-1 custom-scrollbar">
          <div
            v-for="(order, index) in formattedBuyOrders"
            :key="`buy-${index}`"
            class="relative bg-white p-3 rounded-lg hover:shadow-md transition-all duration-200 cursor-pointer border border-transparent hover:border-green-300"
          >
            <div class="flex justify-between items-center">
              <span class="font-bold text-green-600 text-base">
                ${{ formatDecimal(order.price, 2) }}
              </span>
              <span class="text-gray-700 font-medium">
                {{ formatDecimal(order.amount, 8) }}
              </span>
            </div>
            <!-- Depth visualization -->
            <div class="absolute inset-0 bg-green-100 rounded-lg opacity-20" 
                 :style="{ width: `${(order.amount / Math.max(...formattedBuyOrders.map(o => o.amount))) * 100}%` }">
            </div>
          </div>
          
          <div v-if="formattedBuyOrders.length === 0" class="text-center py-8">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-gray-400 text-sm">No buy orders</p>
          </div>
        </div>
        
        <!-- Buy Total -->
        <div v-if="totalBuy > 0" class="mt-4 pt-4 border-t border-green-200 bg-white rounded-lg p-3">
          <div class="flex justify-between items-center">
            <span class="text-sm text-gray-600 font-medium">Total Buy Volume:</span>
            <span class="font-bold text-green-700">{{ formatDecimal(totalBuy, 8) }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Spread -->
    <div v-if="hasSpread" class="mt-6 pt-6 border-t border-gray-200">
      <div class="bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200 rounded-lg p-4">
        <div class="flex justify-between items-center">
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
            </svg>
            <span class="text-gray-700 font-semibold">Market Spread:</span>
          </div>
          <div class="flex items-center gap-3">
            <span 
              :class="{
                'text-red-600 bg-red-50': spreadDirection === 'positive',
                'text-green-600 bg-green-50': spreadDirection === 'negative'
              }"
              class="font-bold text-lg px-3 py-1 rounded"
            >
              ${{ formattedSpread }}
            </span>
            <span class="text-sm text-gray-500 font-medium">
              {{ spreadDirection === 'positive' ? '(Ask > Bid)' : '(Bid > Ask)' }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useOrderStore } from '../stores/orderStore';
import { parseDecimal, formatDecimal, safeNumber } from '../utils/numberFormatter.js';

const store = useOrderStore();

console.log(store.orderbook)

// Format orderbook data with proper number conversion
const formattedSellOrders = computed(() => {
  if (!store.orderbook.sell || !Array.isArray(store.orderbook.sell)) {
    return [];
  }
  
  return store.orderbook.sell
    .slice(0, 10) // Show top 10
    .map(order => ({
      price: parseDecimal(order?.price || 0),
      amount: parseDecimal(order?.amount || 0)
    }));
});

const formattedBuyOrders = computed(() => {
  if (!store.orderbook.buy || !Array.isArray(store.orderbook.buy)) {
    return [];
  }
  
  return store.orderbook.buy
    .slice(0, 10) // Show top 10
    .map(order => ({
      price: parseDecimal(order?.price || 0),
      amount: parseDecimal(order?.amount || 0)
    }));
});

// Calculate totals with safe number handling
const totalSell = computed(() => {
  if (!store.orderbook.sell || !Array.isArray(store.orderbook.sell)) {
    return 0;
  }
  
  return store.orderbook.sell.reduce((sum, order) => {
    return sum + parseDecimal(order?.amount || 0);
  }, 0);
});

const totalBuy = computed(() => {
  if (!store.orderbook.buy || !Array.isArray(store.orderbook.buy)) {
    return 0;
  }
  
  return store.orderbook.buy.reduce((sum, order) => {
    return sum + parseDecimal(order?.amount || 0);
  }, 0);
});

// Calculate spread with safe number handling
const spread = computed(() => {
  const lowestSell = store.orderbook.sell?.[0]?.price || 0;
  const highestBuy = store.orderbook.buy?.[0]?.price || 0;
  
  return parseDecimal(lowestSell) - parseDecimal(highestBuy);
});

// Additional computed properties for display
const formattedSpread = computed(() => {
  const spreadValue = spread.value;
  return formatDecimal(Math.abs(spreadValue), 2);
});

const hasSpread = computed(() => {
  return spread.value !== 0;
});

const spreadDirection = computed(() => {
  const spreadValue = spread.value;
  if (spreadValue > 0) return 'positive';
  if (spreadValue < 0) return 'negative';
  return 'zero';
});
</script>