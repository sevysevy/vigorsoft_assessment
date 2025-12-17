<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Sign in to Exchange
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Use test credentials
        </p>
      </div>
      <form class="mt-8 space-y-6 bg-white p-8 rounded-lg shadow-md" @submit.prevent="login">
        <div class="space-y-4">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
              Email address
            </label>
            <input
              v-model="form.email"
              id="email"
              type="email"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
              placeholder="test@example.com"
            >
          </div>
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
              Password
            </label>
            <input
              v-model="form.password"
              id="password"
              type="password"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
              placeholder="••••••••"
            >
          </div>
        </div>

        <!-- Test Credentials -->
        <div class="bg-blue-50 border border-blue-200 p-4 rounded-md">
          <h3 class="text-sm font-semibold text-blue-900 flex items-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            Test Credentials
          </h3>
          <div class="mt-2 text-sm text-blue-800 space-y-1">
            <p><span class="font-medium">Email:</span> test@example.com</p>
            <p><span class="font-medium">Password:</span> password</p>
            <p class="mt-2 pt-2 border-t border-blue-200">
              <span class="font-medium">Balance:</span> $100,000 | BTC: 5 | ETH: 20
            </p>
          </div>
        </div>

        <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
          {{ error }}
        </div>

        <div>
          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md shadow transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {{ loading ? 'Signing in...' : 'Sign in' }}
          </button>
        </div>

        <div class="text-center text-sm text-gray-500">
          <p>Don't have an account? Use test credentials above.</p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api.js'
import { useOrderStore } from '../stores/orderStore.js'

const router = useRouter()
const store = useOrderStore()

const loading = ref(false)
const error = ref('')
const form = ref({
  email: 'test@example.com',
  password: 'password'
})

const login = async () => {
  loading.value = true
  error.value = ''
  
  try {
    const response = await api.post('/login', form.value)
    
    if (response.data.token) {
      localStorage.setItem('token', response.data.token)
      
      // Initialize store with user data
      store.user = response.data.user
      
      // Fetch additional user data
      await store.fetchProfile()
      
      // Initialize real-time updates
      store.initRealTimeUpdates()
      
      router.push('/dashboard')
    }
  } catch (err) {
    if (err.response?.status === 401) {
      error.value = 'Invalid email or password'
    } else if (err.response?.status === 422) {
      error.value = 'Please check your input fields'
    } else if (err.code === 'ECONNREFUSED') {
      error.value = 'Cannot connect to server. Make sure backend is running.'
    } else {
      error.value = err.response?.data?.message || 'Login failed. Please try again.'
    }
    console.error('Login error:', err)
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.input-field {
  @apply block w-full   border    focus:outline-none   ;
}

.btn-primary {
  @apply flex justify-center  border border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50;
}
</style>