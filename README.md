# 🏆 DigiTrade Academy - Official Web Portal & Multi-Page Platform

> **"LEARN • TRADE • EARN"**  
> High-converting, modern, multi-page web platform built for **DigiTrade Academy** with the official **Crimson Maroon & Luxury Gold** brand identity.

---

## 🎨 Brand Design Identity
- **Primary Maroon**: `#80141D` (Crimson Maroon) & `#9E1B24` (Deep Wine Glow)
- **Royal Metallic Gold**: `#D4AF37` (Rich Gold) & `#F2CA52` (Bright Gold Highlight)
- **Fintech Dark Theme**: `#080A0F` & `#131824` with glassmorphic cards and gold glowing accents

---

## 📄 Multi-Page Architecture

The website features dedicated, full-fledged pages:

| Page | File | Description |
| :--- | :--- | :--- |
| **Home** | [`index.html`](index.html) | Main fintech terminal landing page, stats ticker, courses overview, reviews, and admission portal. |
| **Why Us** | [`why-us.html`](why-us.html) | Core Value Pillars (7 Advantages), 24/7 student availability spotlight, live market trading breakdown. |
| **Courses & Curriculum** | [`courses.html`](courses.html) | In-depth modules for **Forex Trading Mastery** (Engulfing Theory, SMC, ICT, Liquidity, MT5), **Meta Ads**, and **FB Marketplace**. |
| **Student Results** | [`results.html`](results.html) | Verified student reviews, +1,420 pips testimonials, funded trader account passes, 4.8x ROAS agency proofs. |
| **Online Admission** | [`admission.html`](admission.html) | Fast 60-second admission form with dual WhatsApp line selector, perks, contact cards, and FAQ accordion. |

---

## 🚀 Key Features & Mentor Implementations

1. **24/7 Mentor Availability**:
   - *"Our mentors (Muhammad Taha & Muhammad Safiullah) are 100% available 24/7 for students both during the course and for lifetime after completion."*
   - Dedicated highlight cards and lifetime guarantee banners across pages.
2. **Live Market Trading Terminal (+7,000 Pips / $7,000.00)**:
   - *"Watch mentors execute live setups. Learn from basic to advance including secret concepts and strategies to enhance your daily and regular profit: price action, liquidity sweeps, advance engulfing theory, risk-to-reward, market structuring, and fundamental news breakdown in real-time."*
3. **Comprehensive Forex Mastery Syllabus (Mentored by Muhammad Safiullah)**:
   - 🕯️ **Engulfing Theory – Core Focus**: Bullish & Bearish, high probability setups, trend/reversal/continuation, entry/SL/TP.
   - 🧠 **Smart Money Concepts (SMC)**: Order flow, Order Blocks, Breaker Blocks, FVG, BOS, CHOCH, displacement.
   - 🏛️ **ICT Concepts**: 2022 Mentorship model, premium & discount pricing zones, ICT daily bias.
   - 💧 **Liquidity & Liquidity Grabs**: Pools, equal highs/lows, sweep & hunt before expansion.
   - 📊 **Price Action & Market Structure**: Candlestick psychology, dynamic supply/demand zones.
   - 📰 **Fundamental News Trading**: CPI, NFP, FOMC high-impact analysis.
4. **Meta Ads & Facebook Marketplace (Mentored by Muhammad Taha)**:
   - Includes real ad spend, Conversions API, and zero-inventory Marketplace dropshipping.
5. **Dual WhatsApp Mentor Lines**:
   - **Muhammad Taha (Digital Marketing & Meta Ads)**: `+92 340 5201175`
   - **Muhammad Safiullah (Forex & Crypto Trading)**: `+92 332 7292282`
   - Interactive selector in the admission form allowing students to route directly to either mentor.
6. **Luxury Button Hover Effects & Active Social Links**:
   - Shimmer light reflection sweep and elevation animations on all action buttons.
   - Connected Facebook and Instagram channels.

---

## 📱 How to Customize WhatsApp Numbers

You can change or configure the WhatsApp numbers anytime in **`assets/js/main.js`**:

```javascript
const ACADEMY_CONFIG = {
  whatsapp1: '923405201175', // WhatsApp Line 1 (+92 340 5201175)
  whatsapp2: '923327292282', // WhatsApp Line 2 (+92 332 7292282)
  academyName: 'DigiTrade Academy',
  tagline: 'LEARN • TRADE • EARN',
  facebookUrl: 'https://www.facebook.com/digitradeacademy',
  instagramUrl: 'https://www.instagram.com/digitradeacademy'
};
```

---

## 🌐 100% Free Live Deployment Guide

You can publish this website live on the internet with a custom URL for **100% free** in under 2 minutes:

### Option 1: Deploy with Netlify (Fastest - Drag & Drop)
1. Visit [https://app.netlify.com/drop](https://app.netlify.com/drop) (free account).
2. Drag and drop this entire project folder into the Netlify upload box.
3. Your multi-page site will be live instantly with a free SSL HTTPS link (e.g. `https://digitrade-academy.netlify.app`)!

### Option 2: Deploy with Vercel
1. Visit [https://vercel.com](https://vercel.com) and log in.
2. Click **"Add New Project"** and import or run `npx vercel` in terminal.
3. Your site deploys instantly with ultra-fast global CDN.

### Option 3: Deploy with GitHub Pages
1. Create a repository on GitHub.
2. Push all files to the repository.
3. Go to **Settings** > **Pages** > Set source to `main` branch > Click **Save**.

---

## 📁 Project Structure
```
├── assets/
│   ├── css/
│   │   └── style.css            # Luxury Maroon & Gold design system, animations & responsive grid
│   ├── images/
│   │   ├── digitrade-logo.jpg   # Official brand logo
│   │   └── logo.svg             # Vector brand crest fallback
│   └── js/
│       └── main.js              # Multi-page nav, dual WhatsApp dispatcher, tickers & counters
├── index.html                   # Official Homepage (Full Showcase)
├── why-us.html                  # Why Choose Us (Value Pillars & 24/7 Support)
├── courses.html                 # Courses & Detailed Curriculum (SS4 Syllabus)
├── results.html                 # Student Results & Verified Reviews
├── admission.html               # Online Admission & Dual WhatsApp Portal
├── index.htm                    # Instant redirect helper to index.html
└── README.md                    # Documentation & setup guide
```

---
*DigiTrade Academy — Empowering modern earners with actionable, high-income skills.*
