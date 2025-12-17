<template>
  <div class="card">

    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
          <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
        </svg>
        Your Orders
      </h2>
      <div class="flex items-center gap-2">
        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
        <span class="text-xs text-gray-500 font-medium">Auto-refresh</span>
      </div>
    </div>
    

    <div class="flex space-x-2 mb-6 bg-gray-100 p-1.5 rounded-lg">
      <button
        v-for="tab in tabs"
        :key="tab.value"
        @click="activeTab = tab.value"
        :class="[
          'flex-1 px-4 py-2.5 rounded-md font-semibold transition-all duration-200 text-sm',
          activeTab === tab.value
            ? 'bg-white text-blue-600 shadow-md transform scale-105'
            : 'text-gray-600 hover:text-gray-900'
        ]"
      >
        <div class="flex items-center justify-center gap-2">
          <span>{{ tab.label }}</span>
          <span 
            :class="[
              'px-2 py-0.5 rounded-full text-xs font-bold',
              activeTab === tab.value 
                ? 'bg-blue-100 text-blue-700' 
                : 'bg-gray-200 text-gray-600'
            ]"
          >
            {{ getOrdersCount(tab.value) }}
          </span>
        </div>
      </button>
    </div>
    

    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
          <tr>
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
              Filled
            </th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
              Status
            </th>
            <th class="px-4 py-3.5 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="order in filteredOrders"
            :key="order.id"
            class="hover:bg-blue-50 transition-colors duration-150"
          >
            <td class="px-4 py-4 whitespace-nowrap">
              <div class="flex items-center gap-2">
                <span class="font-bold text-gray-900">{{ order.symbol }}</span>
                <span class="text-xs text-gray-500">{{ order.symbol === 'BTC' ? '₿' : 'Ξ' }}</span>
              </div>
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
              <span
                :class="[
                  'px-3 py-1.5 text-xs font-bold rounded-full inline-flex items-center gap-1.5',
                  order.side === 'buy' 
                    ? 'bg-green-100 text-green-800' 
                    : 'bg-red-100 text-red-800'
                ]"
              >
                <span v-if="order.side === 'buy'">↑</span>
                <span v-else>↓</span>
                {{ order.side.toUpperCase() }}
              </span>
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
              <span class="font-bold text-gray-900">${{ formatNumber(order.price, 2) }}</span>
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
              <span class="font-medium text-gray-700">{{ formatNumber(order.amount, 8) }}</span>
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
              <div class="flex items-center gap-3">
                <div class="flex-1 w-24 bg-gray-200 rounded-full h-2.5 overflow-hidden">
                  <div
                    :class="order.side === 'buy' ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-red-500 to-red-600'"
                    class="h-2.5 rounded-full transition-all duration-500 ease-out"
                    :style="{ width: `${calculateFillPercentage(order)}%` }"
                  ></div>
                </div>
                <span class="text-sm font-semibold text-gray-700 min-w-[3rem] text-right">
                  {{ calculateFillPercentage(order).toFixed(1) }}%
                </span>
              </div>
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
              <span
                :class="[
                  'px-3 py-1.5 text-xs font-bold rounded-full inline-flex items-center gap-1.5',
                  {
                    'bg-yellow-100 text-yellow-800': order.status === 'open',
                    'bg-green-100 text-green-800': order.status === 'filled',
                    'bg-red-100 text-red-800': order.status === 'cancelled'
                  }
                ]"
              >
                <span v-if="order.status === 'open'">⏱</span>
                <span v-else-if="order.status === 'filled'">✓</span>
                <span v-else>✕</span>
                {{ order.status.toUpperCase() }}
              </span>
            </td>
            <td class="px-4 py-4 whitespace-nowrap">
              <button
                v-if="order.status === 'open'"
                @click="cancelOrder(order.id)"
                class="px-4 py-2 text-sm font-semibold text-red-600 bg-red-50 border-2 border-red-200 rounded-lg hover:bg-red-100 hover:border-red-300 transition-all duration-200"
              >
                Cancel
              </button>
              <span v-else class="text-gray-400 text-sm font-medium">—</span>
            </td>
          </tr>
        </tbody>
      </table>
      
      <div
        v-if="filteredOrders.length === 0"
        class="text-center py-12 bg-gray-50"
      >
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-400 font-medium">No orders found</p>
        <p class="text-gray-400 text-sm mt-1">Your {{ activeTab }} orders will appear here</p>
      </div>
    </div>
    
    <div class="mt-6 pt-6 border-t border-gray-200 flex justify-between items-center">
      <div class="flex items-center gap-2 text-sm text-gray-500">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
        </svg>
        <span>Last updated: {{ new Date().toLocaleTimeString() }}</span>
      </div>
      <div class="text-sm">
        <span class="text-gray-500">Total orders: </span>
        <span class="font-bold text-gray-900">{{ filteredOrders.length }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useOrderStore } from '../stores/orderStore';
import { parseDecimal, formatDecimal } from '../utils/numberFormatter.js';

const store = useOrderStore();

const tabs = [
  { label: 'All', value: 'all' },
  { label: 'Open', value: 'open' },
  { label: 'Filled', value: 'filled' },
  { label: 'Cancelled', value: 'cancelled' }
];

const activeTab = ref('all');

// Safe number formatting helper
const formatNumber = (value: any, decimals: number = 2): string => {
  const num = parseDecimal(value);
  return formatDecimal(num, decimals);
};

// Calculate fill percentage safely
const calculateFillPercentage = (order: any): number => {
  const filledAmount = parseDecimal(order.filled_amount);
  const totalAmount = parseDecimal(order.amount);
  
  if (totalAmount <= 0) return 0;
  
  const percentage = (filledAmount / totalAmount) * 100;
  return Math.min(Math.max(percentage, 0), 100); // Clamp between 0-100
};

// Filter orders based on active tab
const filteredOrders = computed(() => {
  if (!store.orders || !Array.isArray(store.orders)) {
    return [];
  }
  
  if (activeTab.value === 'all') {
    return store.orders;
  }
  
  return store.orders.filter(order => order.status === activeTab.value);
});

// Get count for each tab
const getOrdersCount = (status: string) => {
  if (!store.orders || !Array.isArray(store.orders)) {
    return 0;
  }
  
  if (status === 'all') {
    return store.orders.length;
  }
  
  return store.orders.filter(order => order.status === status).length;
};

// Cancel order
const cancelOrder = async (orderId: number) => {
  if (!confirm('Are you sure you want to cancel this order?')) {
    return;
  }
  
  try {
    await store.cancelOrder(orderId);
    alert('Order cancelled successfully');
  } catch (error: any) {
    alert(error.response?.data?.error || 'Failed to cancel order');
  }
};
</script>