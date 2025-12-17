# Exchange App

A simple Laravel API + Vue.js frontend app for placing limit orders, matching trades, and real-time updates via Pusher.

---

## Tech Stack

- Backend: Laravel (latest stable)  
- Frontend: Vue.js 3 (Composition API) + Vite + TailwindCSS  
- Database: MySQL  
- Real-time: Pusher via Laravel Broadcasting  

---

## Requirements

- PHP >= 8.2  
- Composer  
- Node.js >= 18  
- MySQL  
- Pusher account  

---

## Setup

### 1. Clone the repository

```bash
git clone <repo_url>
cd <repo_folder>
cd <frontend>
cd <backend>
php artisan migrate:fresh --seed
cd ../<frontend>
npm run dev   #this will run the laravel api, the vue frontend and the queue listener.
