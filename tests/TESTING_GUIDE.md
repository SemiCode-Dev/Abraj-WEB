# Hotel Booking & Payment Testing Guide

## Overview
This document describes the comprehensive test suite for the hotel booking and payment system, including TBO API integration and room availability verification.

## Test Files Created

### 1. Feature Tests: `tests/Feature/HotelBookingPaymentTest.php`
Comprehensive integration tests covering the complete booking flow.

#### Test Cases:

1. **test_complete_booking_flow_with_payment**
   - Tests the entire booking process from search to confirmation
   - Searches for rooms in hotel ID: 1491912
   - Initiates a booking
   - Generates payment data
   - Simulates successful payment callback
   - Completes booking with TBO API
   - Verifies booking status and payment status

2. **test_room_becomes_unavailable_after_booking**
   - **Critical Test**: Verifies room availability behavior
   - Searches for available rooms
   - Books a specific room
   - Searches again for the same dates
   - Confirms the booked room is no longer available
   - **This validates that TBO correctly marks rooms as unavailable**

3. **test_payment_failure_cancels_booking**
   - Creates a pending booking
   - Simulates payment failure
   - Verifies booking is cancelled
   - Checks payment status is marked as failed

4. **test_duplicate_booking_prevention**
   - Creates and completes a booking
   - Attempts to complete the same booking again
   - Verifies no duplicate bookings are created
   - Ensures idempotency of the booking process

5. **test_payment_signature_validation**
   - Tests APS payment signature generation
   - Verifies signature consistency
   - Validates signature changes with different data
   - Ensures security of payment data

6. **test_booking_reference_format**
   - Validates booking reference format (BK-XXXXXXXXXX)
   - Ensures references are unique and properly formatted

7. **test_price_calculation_and_currency**
   - Tests price conversion to smallest unit (cents/fils)
   - Validates multiple currencies (SAR, USD, EUR)
   - Ensures correct amount formatting for APS

8. **test_database_transaction_rollback**
   - Verifies database transactions rollback on failure
   - Ensures data integrity in error scenarios

### 2. Unit Tests: `tests/Unit/PaymentServiceTest.php`
Isolated tests for the PaymentService class.

#### Test Cases:

1. **test_aps_signature_generation** - SHA256 signature generation
2. **test_signature_is_consistent** - Signature consistency verification
3. **test_signature_changes_with_data** - Signature uniqueness
4. **test_signature_ignores_null_values** - Null value handling
5. **test_payment_data_generation** - Payment data structure
6. **test_amount_conversion_to_smallest_unit** - Amount formatting
7. **test_currency_uppercase_conversion** - Currency normalization
8. **test_payment_data_defaults** - Default value handling
9. **test_payment_data_includes_required_fields** - Required fields validation
10. **test_signature_verification** - Signature verification logic
11. **test_payment_reference_format** - Reference format validation

### 3. Factory: `database/factories/HotelBookingFactory.php`
Factory for generating test booking data with various states.

#### States Available:
- `confirmed()` - Confirmed booking with payment
- `cancelled()` - Cancelled booking
- `failed()` - Failed booking
- `paid()` - Paid but not confirmed

## Running the Tests

### Run All Tests
```bash
php artisan test
```

### Run Feature Tests Only
```bash
php artisan test --testsuite=Feature
```

### Run Unit Tests Only
```bash
php artisan test --testsuite=Unit
```

### Run Specific Test File
```bash
php artisan test tests/Feature/HotelBookingPaymentTest.php
```

### Run Specific Test Method
```bash
php artisan test --filter test_room_becomes_unavailable_after_booking
```

### Run with Verbose Output
```bash
php artisan test --verbose
```

### Run with Coverage (if xdebug is installed)
```bash
php artisan test --coverage
```

## Test Configuration

### Environment Variables Required
Make sure your `.env.testing` file has:

```env
DB_CONNECTION=mysql
DB_DATABASE=abraj_testing

# TBO API Credentials
TBO_API_URL=https://api.tbo.com
TBO_USERNAME=your_username
TBO_PASSWORD=your_password

# APS Payment Gateway
APS_MERCHANT_ID=your_merchant_id
APS_ACCESS_CODE=your_access_code
APS_SHA_REQUEST=your_sha_request_phrase
APS_SHA_RESPONSE=your_sha_response_phrase
APS_PAYMENT_URL=https://sbcheckout.payfort.com/FortAPI/paymentPage
```

### Database Setup for Testing
```bash
# Create testing database
php artisan db:create abraj_testing

# Run migrations for testing
php artisan migrate --env=testing
```

## Key Behaviors Tested

### 1. Room Availability After Booking
The test `test_room_becomes_unavailable_after_booking` specifically validates:
- When a room is booked through TBO
- The same room should not appear in subsequent searches for the same dates
- This ensures TBO correctly manages room inventory
- Prevents double-booking scenarios

### 2. Payment Flow
- Payment data generation with correct signature
- Amount conversion to smallest unit (fils/cents)
- Callback verification and signature validation
- Payment status updates

### 3. Booking Status Transitions
```
PENDING → (Payment Success) → PAID → (TBO Booking) → CONFIRMED
PENDING → (Payment Failure) → FAILED/CANCELLED
PAID → (TBO Failure) → FAILED (triggers refund)
```

### 4. Data Integrity
- Database transactions ensure atomicity
- Rollback on failures prevents partial data
- Duplicate prevention ensures idempotency

## Expected Test Results

### Successful Test Run
All tests should pass if:
- TBO API credentials are valid
- Hotel ID 1491912 has available rooms
- APS payment configuration is correct
- Database is properly configured

### Handling TBO API Limitations
Some tests may fail if:
- Hotel 1491912 has no available rooms
- TBO API session expires
- Network connectivity issues

In such cases, the tests will log detailed information about the failure reason.

## Debugging Failed Tests

### Enable Detailed Logging
```bash
# View logs during test execution
tail -f storage/logs/laravel.log
```

### Check Test Database
```bash
# Access testing database
php artisan tinker --env=testing
>>> \App\Models\HotelBooking::all();
```

### Inspect TBO Responses
Tests log all TBO API responses. Check logs for:
- PreBook responses
- Book responses
- Error messages from TBO

## Best Practices

1. **Run tests before deployment** to ensure payment flow works
2. **Monitor test logs** for TBO API issues
3. **Update test hotel ID** if 1491912 becomes unavailable
4. **Keep test database separate** from development/production
5. **Review failed bookings** in test database for debugging

## Continuous Integration

Add to your CI/CD pipeline:
```yaml
test:
  script:
    - cp .env.testing .env
    - php artisan migrate --force
    - php artisan test --parallel
```

## Test Coverage Goals

- **Payment Service**: 100% coverage
- **Booking Service**: 90%+ coverage
- **Controllers**: 80%+ coverage
- **Integration Flow**: All critical paths tested

## Maintenance

- Update tests when TBO API changes
- Add new tests for new payment methods
- Review and update test hotel IDs periodically
- Keep test data realistic and up-to-date
