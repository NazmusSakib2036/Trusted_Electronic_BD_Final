#!/bin/bash

# Queue Worker Script for TrustedElectronics SMS System
# This script starts the Laravel queue worker to process SMS notifications

echo "Starting Laravel Queue Worker for SMS Notifications..."
echo "Press Ctrl+C to stop the worker"

cd "c:\xampp\htdocs\Client_project\TrustedElectronics\backend"

# Start the queue worker with restart capability
php artisan queue:work --timeout=60 --tries=3 --rest=1