# Room Availability & Filtering Logic ✅

## Final Approach: Hybrid Safety
We filter **BOTH** `CONFIRMED` and `PENDING` bookings locally.

### Why this logic?
1. **CONFIRMED Bookings:** TBO can be slow to update or we might hit a stale cache. By filtering locally, we guarantee the booked room is removed instantly from search results.
2. **PENDING Bookings:** When User A is in the payment process, the room is still "available" in TBO. We must filter it locally to prevent User B from trying to book it (Race Condition).
3. **FAILED Bookings:** We do NOT filter these (even if paid), to allow re-booking attempts in case of API glitches.

## How It Works

### Step 1: Count Reserved Rooms
We query the local database to count how many rooms of each type are "reserved" for the user's selected dates.
"Reserved" means:
- Status is **CONFIRMED** (Already booked)
- OR Status is **PENDING** (Payment in progress)
- AND Dates overlap with the user's search

```sql
SELECT room_name, COUNT(*) 
FROM hotel_bookings
WHERE hotel_code = ?
  AND booking_status IN ('confirmed', 'pending')
  AND check_in < ?   -- search checkout
  AND check_out > ?  -- search checkin
GROUP BY room_name
```

### Step 2: Adjust TBO Availability
We take the results from TBO and subtract the local reserved count.

**Example:**
- **TBO Returns:** 10 "Standard Double" rooms
- **Local DB:** 1 Confirmed + 1 Pending = 2 Reserved
- **Calculation:** 10 - 2 = 8
- **Result:** User sees 8 available rooms

### Step 3: Show to User
The user sees the adjusted count. This prevents:
- Double bookings
- Booking conflicts during payment
- Seeing rooms that were just booked (stale cache)

## Files Modified
- `app/Http/Controllers/Web/V1/HotelController.php` (Search & Details methods)
- `app/Services/Api/V1/BookingService.php` (Cache clearing)
- `app/Services/Api/V1/PaymentService.php` (Success message)
