# Authentication & Profile API Documentation

## Base URL
```
https://your-domain.com/api
```

---

## Public Endpoints

### 1. Register (Create Passenger Account)

Create a new passenger account.

**Endpoint:** `POST /register`

**Authentication:** Not Required

**Request Headers:**
```http
Accept: application/json
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john.doe@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "09123456789",
  "address": "123 Main Street, City"
}
```

**Request Fields:**
| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| name | string | Yes | max:255 | Full name |
| email | string | Yes | valid email, unique | Email address |
| password | string | Yes | min:8 | Password |
| password_confirmation | string | Yes | must match password | Password confirmation |
| phone | string | Yes | max:20 | Phone number |
| address | string | No | - | Home address |

**Success Response (201 Created):**
```json
{
  "message": "Registration successful",
  "data": {
    "user": {
      "id": 8,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "role": "passenger",
      "phone": "09123456789",
      "employee_id": null,
      "license_number": null
    },
    "token": "1|abc123def456..."
  }
}
```

**Error Responses:**

**422 Unprocessable Entity (Validation Error):**
```json
{
  "message": "The email has already been taken. (and 1 more error)",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password confirmation does not match."]
  }
}
```

**422 Unprocessable Entity (Password too short):**
```json
{
  "message": "The password field must be at least 8 characters.",
  "errors": {
    "password": ["The password field must be at least 8 characters."]
  }
}
```

---

### 2. Login

Authenticate and receive access token.

**Endpoint:** `POST /login`

**Authentication:** Not Required

**Request Headers:**
```http
Accept: application/json
Content-Type: application/json
```

**Request Body:**
```json
{
  "email": "john.doe@example.com",
  "password": "password123",
  "device_name": "mobile-app"
}
```

**Request Fields:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| email | string | Yes | User's email |
| password | string | Yes | User's password |
| device_name | string | Yes | Device identifier |

**Success Response (200 OK):**
```json
{
  "message": "Login successful",
  "data": {
    "user": {
      "id": 8,
      "name": "John Doe",
      "email": "john.doe@example.com",
      "role": "passenger",
      "phone": "09123456789",
      "employee_id": null,
      "license_number": null
    },
    "token": "2|xyz789abc123..."
  }
}
```

**Error Responses:**

**401 Unauthorized (Invalid Credentials):**
```json
{
  "message": "The provided credentials are incorrect."
}
```

**403 Forbidden (Account Deactivated):**
```json
{
  "message": "Your account has been deactivated. Please contact administrator."
}
```

---

## Protected Endpoints

All endpoints below require authentication.

**Required Header:**
```http
Authorization: Bearer {token}
```

---

### 3. Get Profile

Retrieve authenticated user's profile information.

**Endpoint:** `GET /profile`

**Authentication:** Required

**Request Headers:**
```http
Authorization: Bearer {token}
Accept: application/json
```

**Request Body:** None

**Success Response (200 OK):**
```json
{
  "user": {
    "id": 8,
    "name": "John Doe",
    "email": "john.doe@example.com",
    "role": "passenger",
    "phone": "09123456789",
    "employee_id": null,
    "license_number": null
  }
}
```

**Error Responses:**

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

### 4. Update Profile

Update authenticated user's profile information.

**Endpoint:** `PUT /profile`

**Authentication:** Required

**Request Headers:**
```http
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

**Request Body (Update Basic Info):**
```json
{
  "name": "John Updated Doe",
  "email": "john.updated@example.com",
  "phone": "09987654321",
  "address": "456 New Street, City"
}
```

**Request Body (Update Password):**
```json
{
  "current_password": "oldpassword123",
  "new_password": "newpassword123",
  "new_password_confirmation": "newpassword123"
}
```

**Request Body (Update Both):**
```json
{
  "name": "John Updated Doe",
  "phone": "09987654321",
  "current_password": "oldpassword123",
  "new_password": "newpassword123",
  "new_password_confirmation": "newpassword123"
}
```

**Request Fields:**
| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| name | string | No | max:255 | Full name |
| email | string | No | valid email, unique | Email address |
| phone | string | No | max:20 | Phone number |
| address | string | No | - | Home address |
| current_password | string | Required with new_password | - | Current password for verification |
| new_password | string | No | min:8 | New password |
| new_password_confirmation | string | Required with new_password | must match new_password | New password confirmation |

**Success Response (200 OK):**
```json
{
  "message": "Profile updated successfully",
  "data": {
    "id": 8,
    "name": "John Updated Doe",
    "email": "john.updated@example.com",
    "role": "passenger",
    "phone": "09987654321",
    "employee_id": null,
    "license_number": null
  }
}
```

**Error Responses:**

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

**422 Unprocessable Entity (Wrong Current Password):**
```json
{
  "message": "Current password is incorrect",
  "errors": {
    "current_password": ["The current password is incorrect."]
  }
}
```

**422 Unprocessable Entity (Email Already Taken):**
```json
{
  "message": "The email has already been taken.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

**422 Unprocessable Entity (Password Confirmation Mismatch):**
```json
{
  "message": "The new password confirmation does not match.",
  "errors": {
    "new_password": ["The new password confirmation does not match."]
  }
}
```

---

### 5. Logout

Revoke the current access token.

**Endpoint:** `POST /logout`

**Authentication:** Required

**Request Headers:**
```http
Authorization: Bearer {token}
Accept: application/json
```

**Request Body:** None

**Success Response (200 OK):**
```json
{
  "message": "Logged out successfully"
}
```

**Error Responses:**

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

## Common Scenarios

### 1. New User Registration Flow
```
1. POST /register
2. Receive token
3. Use token for authenticated requests
```

### 2. Existing User Login Flow
```
1. POST /login
2. Receive token
3. Use token for authenticated requests
```

### 3. Update Profile Without Password
```
1. PUT /profile (with name, email, phone, address)
2. No password fields needed
```

### 4. Change Password
```
1. PUT /profile (with current_password, new_password, new_password_confirmation)
2. Current password must be correct
```

### 5. Update Profile AND Change Password
```
1. PUT /profile (with all fields)
2. Include both profile fields and password fields
```

---

## Security Notes

1. **Password Requirements:**
   - Minimum 8 characters
   - Must be confirmed during registration and password changes

2. **Email Uniqueness:**
   - Each email can only be registered once
   - Changing email requires it to be unique across all users

3. **Token Management:**
   - Tokens are device-specific (identified by device_name)
   - Logout revokes only the current token
   - Users can have multiple active tokens (multiple devices)

4. **Account Status:**
   - Newly registered accounts are active by default
   - Deactivated accounts cannot login
   - Only admins can deactivate accounts

---

## Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success - Request completed successfully |
| 201 | Created - Resource created successfully (registration) |
| 401 | Unauthorized - Missing or invalid authentication token |
| 403 | Forbidden - Account deactivated |
| 422 | Unprocessable Entity - Validation failed |
| 500 | Internal Server Error - Server-side error |
