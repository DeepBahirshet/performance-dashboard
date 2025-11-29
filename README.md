🚀 Offer Performance & Redemption Forecasting Dashboard

Laravel 12 + Vue 3 + Inertia.js + Chart.js + PostgreSQL

A production-style module for managing promotional offers, tracking redemptions, and visualizing offer performance using analytics + 14-day forecast predictions.

This assignment demonstrates backend architecture, Vue dashboards, SQL analytics, and async forecast computation using Laravel Jobs.



📦 Features
✅ Offer Management (CRUD)

Create, update, delete promotional offers

Fields: name, code, budget, discount type (%, flat), value, validity dates, max redemptions

✅ Redemption API

POST /api/offers/{offer}/redeem

Validates:

Offer validity (start_date/end_date)

Budget remaining

Max redemptions

Prevent expired usage

Computes:

discount_given (based on offer type)

redemption_date (server time)

✅ Dashboard (Analytics + Charts)

For selected offer:

Daily Redemption Chart

Cumulative Redemption Trend

Budget Utilization (Spent vs Remaining)

Forecast Chart (Next 14 Days)

Moving Average

Dashed predicted line

Supports cached forecast via database

✅ KPIs

Total redemptions

Total discount given

Remaining budget

Redemption rate (% used from max_redemptions)

Average daily redemption

✅ Forecasting Engine (Async)

Background job generates predictions using Moving Average

Data stored in offer_forecasts table

Dashboard pulls cached predictions instantly

Fallback to on-the-fly forecast when cache missing

✅ Clean Architecture

OfferService — business rules

OfferAnalyticsRepository — analytics queries

ForecastService — prediction algorithms

ComputeOfferForecast Job — async forecast generation

API + Inertia frontend separation



⚙️ Tech Stack

Laravel 12

Inertia.js

Vue 3

Chart.js

PostgreSQL

Laravel Queues (database driver)

TailwindCSS (optional UI enhancement)



🛠 Installation & Setup
1. Clone project
git clone <repo-url>
cd <project-folder>

2. Install PHP dependencies
composer install

3. Install Node dependencies
npm install

4. Environment Setup

Duplicate .env.example → .env and configure DB:

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=offer_dashboard
DB_USERNAME=postgres
DB_PASSWORD=postgres


Set queue driver:

QUEUE_CONNECTION=database

5. Generate app key
php artisan key:generate

6. Run migrations
php artisan migrate


(Optional) Seed sample offers + redemptions:

php artisan db:seed

7. Build frontend

Dev:

npm run dev


Prod:

npm run build

8. Start queue worker (for forecasts)
php artisan queue:work

9. Serve app
php artisan serve


🔥 API Usage
Redeem Offer
POST /api/offers/{offer}/redeem

Body (JSON)
{
  "customer_id": 1001,
  "order_amount": 500
}

What backend calculates:

discount_given (percent/flat based on offer settings)

redemption_date (today)

Validity checks

Budget checks

Max redemption checks

Success Response
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



📊 Dashboard (Inertia + Vue + Chart.js)

Access:

/admin/offers/{offer}/dashboard


Charts:

Daily Redemption

Cumulative Redemption

Budget Utilization

14-Day Forecast (dashed line)

KPIs displayed above charts.



🔮 Forecasting (Async Job)
Job

App\Jobs\ComputeOfferForecast

Generates:

14-day prediction

Using Moving Average (window = 7)

Stores in offer_forecasts table

Dispatching

Triggers:

Manually from controller when dashboard loads and cached forecast missing

Automatically after each redemption

Nightly scheduled run