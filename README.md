# Mini Trade Engine

A real-time trading engine built with Laravel 12 and Vue 3 as a technical assignment demonstrating full-stack development skills with focus on financial data integrity, concurrency safety, and real-time systems.

![Laravel](https://img.shields.io/badge/Laravel-12.0-red.svg)
![Vue](https://img.shields.io/badge/Vue-3.5-green.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-purple.svg)

## 📝 Overview

This project fulfills the following requirements:
- **Full Stack Development**: Laravel API backend + Vue.js Composition API frontend
- **Financial Data Integrity**: Atomic transactions, race condition prevention
- **Scalable Balance Management**: Proper asset locking and balance tracking
- **Real-Time Systems**: Pusher WebSocket integration for instant updates
- **Concurrency Safety**: Database locks and transaction isolation

## 🚀 Features

### Core Requirements ✅
- ✅ **Real-Time Trading**: Instant order matching with live WebSocket updates via Pusher
- ✅ **Limit Orders**: Full match implementation (no partial fills)
- ✅ **Financial Integrity**: Atomic transactions with database locks
- ✅ **Commission System**: 1.5% commission on all trades (deducted from buyer)
- ✅ **Balance Locking**: Assets locked for sell orders, USD reserved for buy orders
- ✅ **Race Condition Safe**: `lockForUpdate()` on all critical operations
- ✅ **Private Channels**: User-specific Pusher channels for trade notifications
- ✅ **Live Updates**: Instant balance, asset, and order updates without refresh

### Additional Features
- **Order Management**: View, filter, sort, and cancel open orders
- **Balance History**: Complete transaction ledger with all operations
- **Toast Notifications**: Visual feedback for trades and order placement
- **Modern UI**: Dark-themed responsive interface with Tailwind CSS v4
- **Order Status Tracking**: Cancelled orders show timestamp in tooltip
- **"Mine" Badge**: User's own orders highlighted in orderbook

## 🛠️ Technology Stack

As per assignment requirements:

### Backend
- **Framework**: Laravel 12 (latest stable)
- **Database**: MySQL 8.0+ / PostgreSQL 13+
- **Real-time**: Pusher via Laravel Broadcasting
- **Authentication**: Laravel Sanctum (token-based)
- **Queue**: Laravel Queue for background jobs

### Frontend
- **Framework**: Vue 3.5 (Composition API)
- **Styling**: Tailwind CSS v4
- **Build Tool**: Vite 7
- **HTTP Client**: Axios
- **WebSockets**: Pusher JS
- **Router**: Vue Router 4

### Development Tools
- **Linting**: PHP CS Fixer (PSR-12), ESLint, Prettier
- **Testing**: PHPUnit
- **Process Management**: Concurrently
- **Git Hooks**: Husky + lint-staged


## 📋 System Requirements
- **PHP**: >= 8.2
- **Composer**: >= 2.0
- **Database**: MySQL 8.0+ or PostgreSQL 13+

### Frontend Requirements
- **Node.js**: >= 20.x
- **npm**: >= 10.x

### External Services
- **Pusher Account**: Required for real-time WebSocket functionality
  - Sign up at [pusher.com](https://pusher.com/)

## 🛠️ Installation

### 1. Clone the Repository

```bash
git clone https://github.com/raiyan24r/mini-trade-engine.git
cd mini-trade-engine
```

### 2. Install Dependencies

Using the Makefile (recommended):

```bash
make install
```

Or manually:

```bash
composer install
npm install
```

### 3. Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 4. Configure Database

Edit `.env` and set your database credentials:

**For MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mini_trade_engine
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Create the database:

**MySQL:**
```bash
mysql -u root -p -e "CREATE DATABASE mini_trade_engine;"
```


### 5. Configure Pusher

1. Sign up at [pusher.com](https://pusher.com/) if you haven't already
2. Create a new Channels app
3. Copy your credentials to `.env`:

```env
BROADCAST_CONNECTION=pusher

PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Seed Database (Optional)

Seed with sample users and initial balances:

```bash
php artisan db:seed
```

This creates:
- 2 test users with $10,000 USD balance each
- Sample BTC and ETH holdings

### 8. Build Frontend Assets

**Development:**
```bash
npm run dev
```

**Production:**
```bash
npm run build
```

## 🚀 Running the Application

### Option 1: Using Composer Script (Recommended)

This starts all services concurrently:

```bash
composer dev
```

This will run:
- Laravel development server (http://localhost:8000)
- Queue worker for background jobs
- Pail for real-time logs
- Vite dev server for hot module reloading

### Option 2: Manual Setup

In separate terminal windows:

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Queue Worker:**
```bash
php artisan queue:listen --tries=1
```

**Terminal 3 - Vite Dev Server:**
```bash
npm run dev
```

### Option 3: Using Makefile

```bash
make dev
```

## 🔧 Development Tools

### Code Linting

**Run all linters:**
```bash
make lint
```

**PHP only:**
```bash
make lint-php
```

**JavaScript/Vue only:**
```bash
make lint-js
```

### Auto-fix Issues

**Fix all issues:**
```bash
make fix
```

**PHP only:**
```bash
make fix-php
```

**JavaScript/Vue only:**
```bash
make fix-js
```

**Format with Prettier:**
```bash
make format
```

### Clean Cache

```bash
make clean
```

## 📁 Project Structure

```
mini-trade-engine/
├── app/
│   ├── Events/              # Broadcast events (OrderMatched)
│   ├── Http/Controllers/    # API controllers
│   ├── Models/              # Eloquent models
│   ├── Repositories/        # Data access layer
│   └── Services/            # Business logic (OrderService, BalanceHistoryService)
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/             # Database seeders
├── resources/
│   ├── js/
│   │   ├── components/      # Vue components
│   │   ├── composables/     # Vue composables (useAuth, usePusher, useToast)
│   │   ├── pages/           # Page components
│   │   └── router/          # Vue Router configuration
│   └── views/               # Blade templates
├── routes/
│   ├── api.php              # API routes
│   ├── channels.php         # Broadcast channel authorization
│   └── web.php              # Web routes
```

### Bonus Features Implemented

✅ **Order Filtering**: By symbol, side, status  
✅ **Toast Alerts**: Success, error, info, warning with auto-dismiss  
✅ **Volume Preview**: Real-time total calculation  
✅ **Sorting**: Multi-column with asc/desc toggle  
✅ **Pagination**: Client-side with page navigation  
✅ **Status Badges**: Color-coded (open=amber, filled=green, cancelled=gray)  
✅ **Mine Badge**: Highlight user's orders in orderbook  
✅ **Tooltips**: Cancelled order timestamp on hover  
✅ **Instant Updates**: No refresh needed for any operation like order placed, cancelled

## 🔐 Security Features

- **Authentication**: Laravel Sanctum token-based auth
- **Authorization**: Private Pusher channels with user verification
- **CSRF Protection**: Built-in Laravel CSRF protection
- **SQL Injection Prevention**: Eloquent ORM with prepared statements
- **XSS Protection**: Vue 3 automatic escaping

### 🔥 Core Order Matching & Real-Time Integration

**Order Matching Flow:**
```
1. User submits POST /api/orders with symbol, side, price, amount
2. Backend locks user record (pessimistic locking)
3. Validates balance/assets
4. Creates order with status=OPEN
5. IMMEDIATELY searches for matching counter-order
6. If match found (price & amount match):
   - Locks both orders with lockForUpdate()
   - Executes atomic trade transaction
   - Updates both user balances/assets
   - Deducts 1.5% commission from buyer
   - Marks both orders as FILLED
   - Creates trade record in database
   - Commits transaction
7. Broadcasts OrderMatched event to BOTH parties via Pusher
8. Returns to buyer: order details + trade details (if matched)
```

**Real-Time Broadcasting:**
```
Event: OrderMatched
Payload: {
  buyer_id, seller_id, symbol, price, amount, 
  buy_order_id, sell_order_id, trade_id
}

Channels: 
  - private-user.{buyer_id}
  - private-user.{seller_id}

Trigger: ShouldBroadcastNow (synchronous, no queue delay)
```

**Frontend Real-Time Updates (No Refresh):**
```javascript
// Each user's private channel listener
channel.bind('order.matched', (data) => {
  // Determine role: buyer or seller
  const isMe = userId === data.buyer_id || userId === data.seller_id
  
  // Update UI instantly:
  updateBalances()           // Refresh USD/BTC/ETH
  updateAssets()             // Refresh locked amounts
  updateOrderStatus()        // Mark order as FILLED
  updateOrderbook()          // Remove matched orders
  showToastNotification()    // "Trade complete! Buy 0.01 BTC @ $95,000"
})
```

---


## 📊 API Endpoints

#### Authentication
| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/api/register` | Register new user |
| POST | `/api/login` | Login user (returns Sanctum token) |
| POST | `/api/logout` | Logout user |
| GET | `/api/user` | Get authenticated user |

#### Profile & Balance
| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/api/profile` | **Required**: Returns authenticated user's USD balance + asset balances (BTC, ETH) |

#### Orders (Core Trading)
| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/api/orders?symbol={symbol}` | **Required**: Returns all open orders for orderbook (buy & sell sides) |
| POST | `/api/orders` | **Required**: Creates a limit order (triggers matching) |
| POST | `/api/orders/{id}/cancel` | **Required**: Cancels an open order and releases locked USD/assets |
| GET | `/api/my-orders` | Get user's order history (open + filled + cancelled) |

#### Balance History (Bonus)
| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/api/balance-history` | Get user's complete transaction ledger |

#### Broadcasting
| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/api/broadcasting/auth` | Authorize private channel access for Pusher |


## 🐛 Troubleshooting

### Pusher Connection Issues

**Problem**: "Pusher not available" error

**Solution**:
1. Verify Pusher credentials in `.env`
2. Check that `BROADCAST_CONNECTION=pusher`
3. Ensure Vite variables are prefixed with `VITE_`
4. Restart dev server after `.env` changes


### WebSocket 403 Forbidden

**Problem**: Channel authorization failing

**Solution**:
1. Check `routes/channels.php` exists
2. Verify route is registered in `bootstrap/app.php`
3. Ensure user is authenticated
4. Check browser console for auth token

### Code Style

- PHP: PSR-12 (enforced by PHP CS Fixer)
- JavaScript/Vue: ESLint + Prettier
- Run `make fix` before committing