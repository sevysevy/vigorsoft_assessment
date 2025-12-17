<template>
  <div class="card">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
          <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
          <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/>
        </svg>
        Wallet Overview
      </h2>
      <div class="flex items-center gap-2">
        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
        <span class="text-xs text-gray-500 font-medium">Live</span>
      </div>
    </div>

    <!-- USD Balance Card -->
    <div class="mb-6 bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-lg p-5">
      <div class="flex justify-between items-start mb-2">
        <div>
          <h3 class="text-sm font-semibold text-gray-700 mb-1">Total Balance</h3>
          <div class="text-3xl font-bold text-green-700">
            ${{ formatUSD(store.usdBalance) }}
          </div>
        </div>
        <div class="bg-white rounded-lg px-3 py-1.5 shadow-sm">
          <span class="text-xs font-medium text-gray-600">USD</span>
        </div>
      </div>
      <p class="text-xs text-green-700 mt-2">Available for trading</p>
    </div>

    <!-- Asset Selection Tabs -->
    <div class="mb-6">
      <h3 class="text-sm font-semibold text-gray-700 mb-3">Select Asset</h3>
      <div class="flex space-x-2">
        <button
          v-for="symbol in ['BTC', 'ETH']"
          :key="symbol"
          @click="store.setSymbol(symbol)"
          :class="[
            'flex-1 px-4 py-3 rounded-lg font-semibold transition-all duration-200',
            store.selectedSymbol === symbol
              ? 'bg-blue-600 text-white shadow-lg transform scale-105'
              : 'bg-gray-100 text-gray-700 hover:bg-gray-200 hover:shadow'
          ]"
        >
          <div class="flex items-center justify-center gap-2">
            <span v-if="symbol === 'BTC'">₿</span>
            <span v-else>Ξ</span>
            <span>{{ symbol }}</span>
          </div>
        </button>
      </div>
    </div>

    <!-- Selected Asset Details -->
    <div class="mb-6 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-5">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-800">
          {{ store.selectedSymbol }} Holdings
        </h3>
        <span class="px-2.5 py-1 bg-blue-600 text-white text-xs font-bold rounded-full">
          {{ store.selectedSymbol }}
        </span>
      </div>
      
      <div class="space-y-4">
        <!-- Available Balance -->
        <div class="bg-white rounded-lg p-4 shadow-sm">
          <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium text-gray-600 flex items-center gap-2">
              <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              Available
            </span>
            <span class="text-xs text-gray-500">Ready to trade</span>
          </div>
          <div class="text-2xl font-bold text-gray-900">
            {{ formatCrypto(store.assetBalance) }}
          </div>
        </div>

        <!-- Locked Balance -->
        <div class="bg-white rounded-lg p-4 shadow-sm">
          <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium text-gray-600 flex items-center gap-2">
              <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
              </svg>
              Locked
            </span>
            <span class="text-xs text-gray-500">In open orders</span>
          </div>
          <div class="text-lg font-semibold text-gray-700">
            {{ formatCrypto(store.lockedAssetBalance) }}
          </div>
        </div>
      </div>
    </div>

    <!-- All Assets Overview -->
    <div>
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-800">All Assets</h3>
        <span class="text-xs text-gray-500">{{ store.assets.length }} assets</span>
      </div>
      
      <div class="space-y-3">
        <div
          v-for="asset in store.assets"
          :key="asset.symbol"
          :class="[
            'p-4 rounded-lg border-2 transition-all duration-200 cursor-pointer',
            store.selectedSymbol === asset.symbol
              ? 'bg-blue-50 border-blue-300 shadow-md'
              : 'bg-gray-50 border-gray-200 hover:border-gray-300 hover:shadow'
          ]"
          @click="store.setSymbol(asset.symbol)"
        >
          <div class="flex justify-between items-start">
            <div class="flex-1">
              <div class="flex items-center gap-2 mb-1">
                <span class="font-bold text-gray-900 text-lg">{{ asset.symbol }}</span>
                <span v-if="store.selectedSymbol === asset.symbol" class="px-2 py-0.5 bg-blue-600 text-white text-xs font-semibold rounded">
                  Selected
                </span>
              </div>
              <div class="text-sm text-gray-600 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                Locked: {{ formatCrypto(asset.locked_amount) }}
              </div>
            </div>
            <div class="text-right">
              <div class="text-xl font-bold text-gray-900">
                {{ formatCrypto(asset.amount) }}
              </div>
              <div class="text-xs text-green-600 font-medium mt-0.5">Available</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useOrderStore } from '../stores/orderStore';
import { parseDecimal, formatDecimal } from '../utils/numberFormatter.js';

const store = useOrderStore();

// Safe formatting functions
const formatUSD = (value: any) => {
  const num = parseDecimal(value);
  return formatDecimal(num, 2);
};

const formatCrypto = (value: any) => {
  const num = parseDecimal(value);
  return formatDecimal(num, 8);
};

</script>