#!/bin/bash

# Full path to the PHP binary (adjust if different)
PHP_PATH="/usr/bin/php"

# Full path to the cron.php file
SCRIPT_PATH="/var/www/html/github-timeline/src/cron.php"

# Cron expression
CRON_EXPRESSION="*/5 * * * * $PHP_PATH $SCRIPT_PATH"

# Check if cron job already exists
(crontab -l 2>/dev/null | grep -v "$SCRIPT_PATH"; echo "$CRON_EXPRESSION") | crontab -

echo "✅ CRON job added: $CRON_EXPRESSION"
