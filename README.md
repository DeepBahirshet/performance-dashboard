
# Offer Performance & Redemption Forecasting Dashboard

Laravel 12 + Vue 3 + Inertia.js + Chart.js + PostgreSQL

A production-grade analytics module built with Laravel 12, Vue 3, Inertia.js, Chart.js, and PostgreSQL. This module powers offer management, redemption tracking, KPI analytics, and a 14-day forecasting engine powered by Laravel Jobs. 
##

## 📦 Features

### ✨ Offer Management (CRUD)
- Create, update, delete promotional offers
- Includes fields:
  - **name**
  - **code**
  - **budget**
  - **discount_type** (%, flat)
  - **value**
  - **validity dates**
  - **max_redemptions**

##

### ⭐ Redemption API

**Endpoint:**  
`POST /api/offers/{offer}/redeem`

#### 🔐 Validates:
- Offer validity (`start_date` / `end_date`)
- Budget remaining
- Max redemptions (if applicable)
- Prevents expired usage

#### ⚙️ Computes:
- **discount_given** (based on offer type)
- **redemption_date** (server-side timestamp)

##

### ✅ Dashboard (Analytics + Charts)

For each selected offer, the dashboard provides:

- **Daily Redemption Chart**
- **Cumulative Redemption Trend**
- **Budget Utilization** (Spent vs Remaining)
- **14-Day Forecast Chart**
- **Moving Average–based predictions**
- **Dashed line** for predicted values
- **Cached forecast support** via database storage

##

### 🎯 KPIs (Key Performance Indicators)

- **Total Redemptions**
- **Total Discount Given**
- **Remaining Budget**
- **Redemption Rate** (% used from `max_redemptions`)
- **Average Daily Redemption**

##

### 📈 Forecasting Engine (Async)

- Background job computes predictions using **Moving Average**
- Forecast data stored in `offer_forecasts` table
- Dashboard loads cached predictions instantly
- Falls back to **on-the-fly forecast** if cache is missing or stale

##

### 🗂️ Clean Architecture

- **OfferService** — core business logic  
- **OfferAnalyticsRepository** — SQL analytics & aggregations  
- **ForecastService** — prediction algorithms  
- **ComputeOfferForecast** Job — async forecast generation  
- **API + Inertia.js Frontend** separation for clean boundaries  

---

## ⚙️ Tech Stack

- **Laravel 12**
- **Inertia.js**
- **Vue 3**
- **Chart.js**
- **PostgreSQL**
- **Laravel Queues** (database driver)
- **TailwindCSS** (UI enhancement)


---

## 🛠 Installation & Setup

### 1️⃣ Clone the Project
```bash
git clone https://github.com/DeepBahirshet/performance-dashboard.git
cd performance-dashboard
```

### 2️⃣ Install PHP Dependencies
```bash
composer install
 ```

### 3️⃣ Install Node Dependencies
```bash
npm install
 ```
 ### 4️⃣ Environment Setup

 Duplicate the example environment file:

```bash
cp .env.example .env
```

#### Environment Variables
`DB_CONNECTION=pgsql`  
`DB_HOST=127.0.0.1`  
`DB_PORT=5432`  
`DB_DATABASE=performance_dashboard`  
`DB_USERNAME=postgres`  
`DB_PASSWORD=postgres`


#### Set Queue Driver:
`QUEUE_CONNECTION=database`

### 5️⃣ Generate Application Key
```bash
php artisan key:generate
```

### 6️⃣ Run Migrations
```bash
php artisan migrate
```

### 7️⃣ (optional) Seed sample offers:
```bash
php artisan db:seed
```

### 8️⃣ Build frontend
Dev:
```bash
npm run dev
```

Prod:
```bash
npm run build
```

### 9️⃣ Start queue worker (for forecasts)
```bash
php artisan queue:work
```
### 🔟 Serve app
```bash
php artisan serve
```

---
##  🎟️ Offer Management (CRUD)

### Access Offer List (Index)

```bash
/admin/offers
```
### Create New Offer
```bash
/admin/offers/create
```

### Edit Offer
```bash
/admin/offers/{offer}/edit
```

### Delete Offer
From the Offers Index page, click the delete icon.  
A confirmation popup will appear — clicking **Delete** will delete the selected offer.

---

## API Reference

#### Redeem Offer

```bash
  POST /api/offers/{offer}/redeem
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `customer_id` | `integer` | **Required** |
| `order_amount` | `integer` | **Required** |

### Success Response

```json
{
  "status": true,
  "message": "Redeemed",
  "data": {
    "redemption_id": 45,
    "offer_id": 1,
    "remaining_budget": 950,
    "total_redemptions": 1,
    "discount_given": 50
  }
}
```
--- 

## 📊 Dashboard (Analytics + Charts)

### Access:

```bash
/admin/offers/{offer}/dashboard
```

**Charts and Analytics:**

- Daily Redemption

- Cumulative Redemption

- Budget Utilization

- 14-Day Forecast (dashed line)

- KPIs displayed above charts.

