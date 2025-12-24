# ⚠️ Important Notes for Running Tests

## Test Types

### Unit Tests ✅ (Always Safe to Run)
```bash
php artisan test --testsuite=Unit
```
- **Status**: ✅ All passing (11 tests)
- **Duration**: ~2-3 seconds
- **Dependencies**: None (no external APIs)
- **Safe to run**: Yes, anytime

### Feature Tests ⚠️ (Requires Live API)
```bash
php artisan test --testsuite=Feature
```
- **Status**: ⚠️ Requires live TBO API connection
- **Duration**: ~30-60 seconds
- **Dependencies**: TBO API, Database
- **Safe to run**: Only when TBO API is accessible

## Why Feature Tests May Fail

### 1. TBO API Requirements
Feature tests make **real API calls** to TBO to:
- Search for rooms in hotel 1491912
- Verify room availability
- Test PreBook functionality
- Test actual booking flow

### 2. Common Failure Reasons

#### ❌ No Available Rooms
```
Error: No rooms available for selected dates
```
**Solution**: 
- Change test dates in the test file
- Use a different hotel ID
- Check if hotel 1491912 is active in TBO

#### ❌ TBO Session Expired
```
Error: Session Expired
```
**Solution**:
- Run tests again (TBO will create new session)
- Check TBO credentials in `.env`

#### ❌ Hotel Not Found
```
Error: Hotel not found
```
**Solution**:
- Verify hotel ID 1491912 exists in TBO
- Update `$testHotelId` in test file

## Recommended Testing Strategy

### For Development
```bash
# Run unit tests frequently (fast, no dependencies)
php artisan test --testsuite=Unit
```

### Before Deployment
```bash
# Run all tests including feature tests
php artisan test
```

### For CI/CD
Consider using **mocked TBO responses** for feature tests to avoid:
- API rate limits
- Inconsistent test results
- External dependency failures

## Mocking TBO API (Future Enhancement)

To make feature tests more reliable, you can:

1. **Create TBO Mock Service**
```php
// tests/Mocks/MockTboService.php
class MockTboService extends HotelApiService
{
    public function searchHotel(array $data)
    {
        return TestHelper::mockTboSearchResponse();
    }
    
    public function preBook(string $bookingCode)
    {
        return TestHelper::mockTboPreBookResponse($bookingCode);
    }
    
    public function book(array $data)
    {
        return TestHelper::mockTboBookResponse();
    }
}
```

2. **Bind Mock in Tests**
```php
protected function setUp(): void
{
    parent::setUp();
    
    // Use mock TBO service for testing
    $this->app->bind(HotelApiService::class, MockTboService::class);
}
```

## Current Test Status

### ✅ Working Tests
- All Unit Tests (11 tests) - **100% passing**
- Payment signature validation
- Amount conversion
- Data validation

### ⚠️ Integration Tests
- Feature tests require live TBO API
- May fail if hotel has no rooms
- Dependent on external service availability

## Quick Test Commands

```bash
# Safe to run anytime (no external dependencies)
php artisan test tests/Unit/PaymentServiceTest.php

# Requires TBO API access
php artisan test tests/Feature/HotelBookingPaymentTest.php

# Run specific test
php artisan test --filter test_payment_signature_validation

# Run with detailed output
php artisan test tests/Unit/PaymentServiceTest.php --testdox
```

## Best Practices

1. **Always run unit tests** before committing code
2. **Run feature tests** before major deployments
3. **Check TBO API status** before running feature tests
4. **Review logs** if tests fail: `storage/logs/laravel.log`
5. **Keep test hotel ID updated** if hotel becomes unavailable

## Summary

✅ **Unit Tests**: Ready to use, all passing  
⚠️ **Feature Tests**: Created but require live TBO API  
📝 **Documentation**: Complete  
🛠️ **Helpers**: Available for easy testing  

**Recommendation**: Start with unit tests, add TBO mocking for reliable feature tests in the future.
