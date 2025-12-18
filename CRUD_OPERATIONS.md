# CRUD Operations - Admin Only Access

## Overview

This Prison Management System implements **role-based access control** where:

- **Administrators** can perform all CRUD operations (Create, Read, Update, Delete)
- **Regular Users** can only perform READ operations (view data only)

## CRUD Operations by Module

### 1. Inmates Management
- **CREATE** ✅ Admin Only
- **READ** ✅ All Users
- **UPDATE** ✅ Admin Only
- **DELETE** ✅ Admin Only

### 2. Staff Management
- **CREATE** ✅ Admin Only
- **READ** ✅ All Users
- **UPDATE** ✅ Admin Only
- **DELETE** ✅ Admin Only

### 3. Cells Management
- **CREATE** ✅ Admin Only
- **READ** ✅ All Users
- **UPDATE** ✅ Admin Only
- **DELETE** ✅ Admin Only

### 4. Visitors Management
- **CREATE** ✅ Admin Only
- **READ** ✅ All Users
- **UPDATE** ✅ Admin Only
- **DELETE** ✅ Admin Only

### 5. Visits Management
- **CREATE** ✅ Admin Only
- **READ** ✅ All Users
- **UPDATE** ✅ Admin Only
- **DELETE** ✅ Admin Only

### 6. Activities Management
- **CREATE** ✅ Admin Only
- **READ** ✅ All Users
- **UPDATE** ✅ Admin Only
- **DELETE** ✅ Admin Only

### 7. Incidents Management
- **CREATE** ✅ Admin Only
- **READ** ✅ All Users
- **UPDATE** ✅ Admin Only
- **DELETE** ✅ Admin Only

### 8. Medical Records
- **CREATE** ✅ Admin Only
- **READ** ✅ All Users
- **UPDATE** ✅ Admin Only
- **DELETE** ✅ Admin Only

### 9. User Management
- **CREATE** ✅ Admin Only
- **READ** ✅ Admin Only
- **UPDATE** ✅ Admin Only
- **DELETE** ✅ Admin Only

## Implementation Details

### API Level Protection
All API endpoints check for admin privileges:
```php
if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied. Only administrators can perform this operation.']);
    break;
}
```

### UI Level Protection
- "Add" buttons are hidden for regular users
- Edit/Delete buttons are hidden for regular users
- Only "View" buttons are shown to regular users

### Security Features
1. **Server-side validation** - All operations are validated on the server
2. **Role-based checks** - Every CRUD operation checks user role
3. **Error messages** - Clear messages when access is denied
4. **UI restrictions** - Buttons hidden based on user role

## User Experience

### For Administrators
- Full access to all CRUD operations
- Can create, read, update, and delete records
- See all action buttons (Add, Edit, Delete)

### For Regular Users
- Read-only access
- Can view all data
- Can search and filter data
- See only "View" buttons
- Cannot modify any data

## Testing CRUD Operations

### As Administrator:
1. Login with admin credentials
2. Navigate to any module (e.g., Inmates)
3. Click "+ Add New" button
4. Fill form and submit (CREATE)
5. Click "Edit" button (UPDATE)
6. Click "Delete" button (DELETE)
7. View data in tables (READ)

### As Regular User:
1. Login with regular user credentials
2. Navigate to any module
3. "+ Add New" button should NOT be visible
4. "Edit" and "Delete" buttons should NOT be visible
5. Only "View" button should be visible
6. Can view all data in tables (READ)

## Error Messages

When a regular user tries to perform CRUD operations:
- **403 Forbidden** - Access denied
- Message: "Access denied. Only administrators can [operation]."

## Notes

- All CRUD operations are logged in the database
- Cell occupancy is automatically updated when inmates are assigned/moved
- Foreign key constraints prevent orphaned records
- Soft deletes can be implemented by changing status instead of actual deletion


