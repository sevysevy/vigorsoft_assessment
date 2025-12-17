<template>
  <div class="card overflow-hidden">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
          <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
        </svg>
        Place Limit Order
      </h2>
      <span class="px-3 py-1.5 bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 text-xs font-bold rounded-full border border-blue-200 shadow-sm">
        LIMIT ORDER
      </span>
    </div>

    <form @submit.prevent="placeOrder" class="space-y-5">
      <!-- Symbol Selection -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1.5">
            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
            </svg>
            Symbol
          </label>
          <div class="relative">
            <select 
              v-model="form.symbol" 
              class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white font-semibold appearance-none cursor-pointer hover:border-gray-400"
            >
              <option value="BTC">₿ Bitcoin (BTC)</option>
              <option value="ETH">Ξ Ethereum (ETH)</option>
            </select>
            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </div>
        </div>
        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1.5">
            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
            </svg>
            Side
          </label>
          <div class="relative">
            <select 
              v-model="form.side" 
              class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white font-semibold appearance-none cursor-pointer hover:border-gray-400"
            >
              <option value="buy">Buy</option>
              <option value="sell">Sell</option>
            </select>
            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </div>
        </div>
      </div>

      <!-- Price Input -->
      <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1.5">
          <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
          </svg>
          Limit Price
        </label>
        <div class="relative group">
          <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-lg">
            $
          </span>
          <input
            v-model.number="form.price"
            type="number"
            step="0.01"
            min="0.01"
            required
            class="w-full pl-10 pr-4 py-3 border-2 bg-white border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold text-gray-900 hover:border-gray-400"
            placeholder="95,000.00"
          >
        </div>
        <p class="mt-1.5 text-xs text-gray-500 flex items-center gap-1">
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
          </svg>
          Enter your desired execution price
        </p>
      </div>

      <!-- Amount Input -->
      <div>
        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-1.5">
          <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1zm-5 8.274l-.818 2.552c.25.112.526.174.818.174.292 0 .569-.062.818-.174L5 10.274zm10 0l-.818 2.552c.25.112.526.174.818.174.292 0 .569-.062.818-.174L15 10.274z" clip-rule="evenodd"/>
          </svg>
          Amount ({{ form.symbol }})
        </label>
        <div class="relative group">
          <input
            v-model.number="form.amount"
            type="number"
            step="0.00000001"
            min="0.00000001"
            required
            class="w-full pl-4 pr-16 py-3 bg-white border-2 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold text-gray-900 hover:border-gray-400"
            placeholder="0.01000000"
          >
          <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-sm bg-gray-100 px-2 py-1 rounded">
            {{ form.symbol }}
          </span>
        </div>
        <p class="mt-1.5 text-xs text-gray-500 flex items-center gap-1">
          <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
          </svg>
          How much {{ form.symbol }} to {{ form.side }}
        </p>
      </div>

      <!-- Order Summary -->
      <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-5 rounded-xl border-2 border-blue-200 shadow-sm space-y-3">
        <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
          <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
          </svg>
          Order Summary
        </h3>
        
        <div class="space-y-2.5">
          <div class="flex justify-between items-center bg-white p-3 rounded-lg">
            <span class="text-sm text-gray-600 font-medium">Trade Value:</span>
            <span class="font-bold text-gray-900">${{ formatNumber(tradeValue, 2) }}</span>
          </div>
          
          <!-- COMMISSION SECTION - Only show for BUY orders -->
          <div v-if="form.side === 'buy'" class="space-y-2.5">
            <div class="flex justify-between items-center bg-white p-3 rounded-lg">
              <span class="text-sm text-gray-600 font-medium flex items-center gap-1">
                Commission (1.5%):
                <svg class="w-3.5 h-3.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
              </span>
              <span class="font-bold text-red-600">-${{ formatNumber(commission, 2) }}</span>
            </div>
            <div class="flex justify-between items-center pt-3 border-t-2 border-blue-200 bg-gradient-to-r from-white to-blue-50 p-3 rounded-lg">
              <span class="text-base font-bold text-gray-900">Total Cost:</span>
              <span class="text-xl font-bold text-blue-700">${{ formatNumber(netCost, 2) }}</span>
            </div>
          </div>
          
          <!-- SELL ORDER SUMMARY - No commission deduction -->
          <div v-else class="flex justify-between items-center pt-3 border-t-2 border-green-200 bg-gradient-to-r from-white to-green-50 p-3 rounded-lg">
            <span class="text-base font-bold text-gray-900">You Receive:</span>
            <span class="text-xl font-bold text-green-700">${{ formatNumber(tradeValue, 2) }}</span>
          </div>
        </div>
      </div>

      <!-- Validation Messages -->
      <div v-if="validationError" class="bg-red-50 border-l-4 border-red-500 p-3 rounded">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
          </svg>
          <span class="text-sm text-red-700 font-medium">{{ validationError }}</span>
        </div>
      </div>

      <!-- Submit Button -->
      <button
        type="submit"
        :disabled="store.loading || !isValid"
        :class="[
          'w-full py-4 px-6 font-bold rounded-xl transition-all duration-200 shadow-lg text-lg',
          'disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none',
          'transform active:scale-95',
          form.side === 'buy'
            ? 'bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl text-white'
            : 'bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 hover:shadow-xl text-white'
        ]"
      >
        <span v-if="store.loading" class="flex items-center justify-center gap-3">
          <svg class="animate-spin h-6 w-6" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
          </svg>
          <span>Processing Order...</span>
        </span>
        <span v-else class="flex items-center justify-center gap-3">
          <svg v-if="form.side === 'buy'" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
          </svg>
          <svg v-else class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/>
          </svg>
          <span>{{ form.side.toUpperCase() }} {{ form.amount || '0' }} {{ form.symbol }}</span>
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
          </svg>
        </span>
      </button>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { useOrderStore } from '../stores/orderStore';
import { parseDecimal, formatDecimal, safeNumber } from '../utils/numberFormatter.js';

const store = useOrderStore();

const form = ref({
  symbol: 'BTC' as 'BTC' | 'ETH',
  side: 'buy' as 'buy' | 'sell',
  price: '' as string | number,
  amount: '' as string | number
});

const validationError = ref('');

const formatNumber = (value: any, decimals: number = 2): string => {
  const num = parseDecimal(value);
  return formatDecimal(num, decimals);
};

const parseInputNumber = (value: any): number => {
  if (typeof value === 'number') return value;
  if (typeof value === 'string') {
    const num = parseFloat(value);
    return isNaN(num) ? 0 : num;
  }
  return 0;
};

// Computed properties
const priceNum = computed(() => parseInputNumber(form.value.price));
const amountNum = computed(() => parseInputNumber(form.value.amount));

// Trade value (price × amount)
const tradeValue = computed(() => priceNum.value * amountNum.value);

// Commission (only for buy orders)
const commission = computed(() => {
  if (form.value.side === 'buy') {
    return tradeValue.value * 0.015;
  }
  return 0; // No commission for seller
});

// Net cost/receipt
const netCost = computed(() => {
  if (form.value.side === 'buy') {
    return tradeValue.value + commission.value;
  }
  return tradeValue.value; 
});

// Validation
const isValid = computed(() => priceNum.value > 0 && amountNum.value > 0);


// Watch for form changes to clear validation
watch([() => form.value.price, () => form.value.amount], () => {
  validationError.value = '';
});

// Validate balance before placing order
const validateBalance = () => {
  const price = parseInputNumber(form.value.price);
  const amount = parseInputNumber(form.value.amount);
  
  if (form.value.side === 'buy') {
    const requiredBalance = parseDecimal(netCost.value);
    const availableBalance = parseDecimal(store.usdBalance);
    
    if (availableBalance < requiredBalance) {
      validationError.value = `Insufficient balance. Required: $${formatNumber(requiredBalance, 2)}, Available: $${formatNumber(availableBalance, 2)}`;
      return false;
    }
  } else {
    const requiredAmount = parseInputNumber(form.value.amount);
    const availableAmount = parseDecimal(store.assetBalance);
    
    if (availableAmount < requiredAmount) {
      validationError.value = `Insufficient ${form.value.symbol}. Required: ${formatNumber(requiredAmount, 8)}, Available: ${formatNumber(availableAmount, 8)}`;
      return false;
    }
  }
  return true;
};

// Place order
const placeOrder = async () => {
  if (!validateBalance()) return;
  
  try {
    const orderData = {
      symbol: form.value.symbol,
      side: form.value.side,
      price: parseInputNumber(form.value.price),
      amount: parseInputNumber(form.value.amount)
    };
    
    await store.placeOrder(orderData);
    
    // Reset form
    form.value.price = '';
    form.value.amount = '';
    
    alert('Order placed successfully!');
  } catch (error: any) {
    validationError.value = error.response?.data?.error || 'Failed to place order';
  }
};


</script>

<style scoped>
.input-field {
  @apply w-full  border  focus:outline-none focus:ring-2 ;
}
</style>