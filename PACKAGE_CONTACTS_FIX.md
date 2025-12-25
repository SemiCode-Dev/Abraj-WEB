# Package Contacts Fix - Requests Page

## Issue
Package bookings were not appearing on the requests page (`/ar/requests`) even though they existed in the database.

## Root Cause
The `RequestsController` was not fetching package contacts from the `package_contacts` table.

## Solution Implemented

### 1. Updated RequestsController
**File**: `app/Http/Controllers/Web/V1/RequestsController.php`

**Changes**:
- Added `PackageContact` model import
- Fetched package contacts with package relationship
- Added package contacts to the `hasBookings` check
- Passed `packageContacts` to the view

```php
$packageContacts = PackageContact::where('user_id', $user->id)
    ->with('package')
    ->orderBy('created_at', 'desc')
    ->get();
```

### 2. Updated Requests View
**File**: `resources/views/Web/requests.blade.php`

**Changes**:
- Added new "Package Inquiries" section
- Displays all package contacts with:
  - Package title (localized)
  - Contact name, email, phone
  - Package duration
  - Status badge (contacted/pending)
  - Message (if provided)
  - Time since inquiry

### 3. Fixed Package Model Fields
**Issue**: View was trying to access `$contact->package->name` and `$contact->package->destination` which don't exist.

**Fix**: Updated to use correct Package model fields:
- `name` → `locale_title` or `title`
- `destination` → `locale_duration` or `duration`

## Package Contact Display

### Information Shown:
- ✅ Package title (in current language)
- ✅ Contact name
- ✅ Contact email
- ✅ Contact phone
- ✅ Package duration
- ✅ Inquiry message
- ✅ Status (contacted/pending)
- ✅ Time since inquiry

### Status Colors:
- 🟢 **Contacted**: Green badge
- 🟡 **Pending**: Yellow badge
- ⚪ **Other**: Gray badge

## Example Display

```
┌─────────────────────────────────────────────┐
│  📦 Package Inquiries                       │
│  ┌─────────────────────────────────────┐    │
│  │ Package Name          [Pending]     │    │
│  │ 👤 Ahmed                            │    │
│  │ ✉️ ahmed@gmail.com                  │    │
│  │ 📞 1141357100                       │    │
│  │ ⏰ 5 Days                           │    │
│  │                                     │    │
│  │ 💬 "User's message here..."         │    │
│  │                                     │    │
│  │ 2 hours ago                         │    │
│  └─────────────────────────────────────┘    │
└─────────────────────────────────────────────┘
```

## Files Modified

1. ✅ `app/Http/Controllers/Web/V1/RequestsController.php`
   - Added PackageContact model
   - Fetched package contacts
   - Updated hasBookings check

2. ✅ `resources/views/Web/requests.blade.php`
   - Added Package Inquiries section
   - Fixed package field names
   - Added proper icons and styling

## Testing

### Test Steps:
1. ✅ Submit a package inquiry
2. ✅ Visit `/ar/requests`
3. ✅ Verify package inquiry appears
4. ✅ Check all fields display correctly
5. ✅ Verify status badge shows correct color
6. ✅ Verify message displays if provided

### Expected Result:
- Package inquiries now appear in the "Package Inquiries" section
- All booking types (hotels, flights, cars, transfers, visas, packages) display correctly
- "Browse Hotels" button shows when no bookings exist
- "Browse More Hotels" button shows at bottom when bookings exist

## Database Tables Involved

- `package_contacts` - Stores package inquiries
- `packages` - Package details (title, duration, etc.)
- `users` - User information

## Relationships

```
PackageContact
  ├─ belongsTo → Package
  └─ belongsTo → User
```

## Translation Keys

- `__('Package Inquiries')`
- `__('Package Inquiry')`
- `__('Duration')`
- `__(ucfirst($status))`

## Status Values

Package contacts can have the following statuses:
- `pending` - Inquiry submitted, awaiting response
- `contacted` - Admin has contacted the user
- Other custom statuses as defined

---

**Status**: ✅ Fixed and Working
**Date**: December 25, 2025
**Issue**: Package bookings not appearing
**Solution**: Added package contacts to requests page
