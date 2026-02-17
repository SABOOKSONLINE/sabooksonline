# PayFast Subscription Cronjob Setup

## Files
- `payfast-subscription-check.php` - Main cronjob script

## What it does:
1. **Checks for expired subscriptions** - Finds users whose `end_date` has passed
2. **Degrades users** - Sets subscription to "Free" if payment expired
3. **Handles recurring payments** - Checks if PayFast sent renewal notification
4. **Updates renewal status** - Marks subscriptions as "expired" or "failed"

## Setup Instructions

### Option 1: Server Cron (Recommended)
Add to your server's crontab:

```bash
# Run daily at 2 AM
0 2 * * * /usr/bin/php /path/to/your/site/cron/payfast-subscription-check.php >> /path/to/your/site/cron/subscription-check.log 2>&1
```

### Option 2: Xneelo Cron (if available)
1. Log into Xneelo control panel
2. Find "Cron Jobs" or "Scheduled Tasks"
3. Add new cron job:
   - **Command:** `php /usr/www/users/saboobwqsj/cron/payfast-subscription-check.php`
   - **Schedule:** Daily at 2 AM (0 2 * * *)
   - **Output:** Save to log file

### Option 3: External Cron Service
Use a service like:
- EasyCron.com
- Cron-job.org
- SetCronJob.com

Point it to: `https://www.sabooksonline.co.za/cron/payfast-subscription-check.php`

## Testing
Visit the URL directly to test:
```
https://www.sabooksonline.co.za/cron/payfast-subscription-check.php
```

Check the log file: `cron/subscription-check.log`

## Important Notes
- The script logs all actions to `subscription-check.log`
- Users are degraded to "Free" plan when subscription expires
- Recurring subscriptions are checked for renewal payments
- If PayFast doesn't send renewal notification, user is degraded
