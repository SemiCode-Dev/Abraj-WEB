@echo off
REM Hotel Booking & Payment Test Runner
REM This script runs the comprehensive test suite for the booking system

echo ========================================
echo Hotel Booking & Payment Test Suite
echo ========================================
echo.

echo [1/4] Running Unit Tests...
echo ----------------------------------------
php artisan test --testsuite=Unit
if %errorlevel% neq 0 (
    echo.
    echo ERROR: Unit tests failed!
    pause
    exit /b 1
)

echo.
echo [2/4] Running Feature Tests...
echo ----------------------------------------
php artisan test --testsuite=Feature
if %errorlevel% neq 0 (
    echo.
    echo ERROR: Feature tests failed!
    pause
    exit /b 1
)

echo.
echo [3/4] Running Room Availability Test...
echo ----------------------------------------
php artisan test --filter test_room_becomes_unavailable_after_booking
if %errorlevel% neq 0 (
    echo.
    echo WARNING: Room availability test failed - check TBO API
)

echo.
echo [4/4] Running Complete Booking Flow Test...
echo ----------------------------------------
php artisan test --filter test_complete_booking_flow_with_payment
if %errorlevel% neq 0 (
    echo.
    echo WARNING: Complete flow test failed - check TBO API and payment config
)

echo.
echo ========================================
echo Test Suite Completed!
echo ========================================
echo.
echo Check storage/logs/laravel.log for detailed test execution logs
echo.
pause
