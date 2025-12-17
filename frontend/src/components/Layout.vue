<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <!-- Logo/Brand -->
          <div class="flex items-center">
            <div class="flex items-center gap-3">
              <div class="bg-gradient-to-br from-blue-600 to-indigo-600 p-2 rounded-lg shadow-md">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                </svg>
              </div>
              <div>
                <h1 class="text-xl font-bold text-gray-900">Limit Order Exchange</h1>
                <p class="text-xs text-gray-500 font-medium">Trade BTC & ETH</p>
              </div>
            </div>
          </div>

          <!-- User Section -->
          <div class="flex items-center space-x-4">
            <div v-if="store.user" class="flex items-center space-x-4">
              <!-- User Info Card -->
              <div class="hidden sm:flex items-center gap-3 bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-2 rounded-lg border border-blue-200">
                <div class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow">
                  {{ store.user.name.charAt(0).toUpperCase() }}
                </div>
                <div class="text-sm">
                  <div class="font-semibold text-gray-900">{{ store.user.name }}</div>
                  <div class="text-gray-600 text-xs">{{ store.user.email }}</div>
                </div>
              </div>

              <!-- Mobile User Avatar -->
              <div class="sm:hidden bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md">
                {{ store.user.name.charAt(0).toUpperCase() }}
              </div>

              <!-- Logout Button -->
              <button
                @click="logout"
                class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="hidden sm:inline">Logout</span>
              </button>
            </div>

            <!-- Login Button (when not logged in) -->
            <div v-else>
              <router-link
                to="/login"
                class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Login
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-gray-200 bg-white">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
          <div class="flex items-center gap-2 text-sm text-gray-600">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>© 2025 Limit Order Exchange</span>
          </div>
          <div class="flex items-center gap-4">
            <span class="flex items-center gap-2 text-xs text-gray-500">
              <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
              System Online
            </span>
            <span class="text-xs text-gray-400">v1.0.0</span>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import { useOrderStore } from '../stores/orderStore';

const router = useRouter();
const store = useOrderStore();

const logout = () => {
  localStorage.removeItem('token');
  store.cleanup();
  router.push('/login');
};
</script>