# Conductor API Documentation

## Base URL
```
https://your-domain.com/api/conductor
```

## Authentication
All endpoints require authentication using Laravel Sanctum bearer token.

**Header:**
```
Authorization: Bearer {token}
```

---

## Endpoints

### 1. Get Dashboard

Retrieve conductor dashboard with current trip information and today's statistics.

**Endpoint:** `GET /conductor/dashboard`

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
  "message": "Conductor dashboard retrieved successfully",
  "data": {
    "current_trip": {
      "id": 42,
      "trip_date": "2025-11-20",
      "status": "in_progress",
      "passenger_count": 25,
      "bus": {
        "number": "YBL-001",
        "plate": "ABC-1234"
      },
      "route": {
        "name": "Downtown Express",
        "start": "North Terminal",
        "end": "City Center"
      }
    },
    "today_stats": {
      "trips_count": 3
    }
  }
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| current_trip | object\|null | Current active trip (null if no active trip) |
| current_trip.id | integer | Trip ID |
| current_trip.trip_date | date | Date of the trip |
| current_trip.status | string | Trip status (loading/in_progress) |
| current_trip.passenger_count | integer | Current passenger count |
| current_trip.bus.number | string | Bus number |
| current_trip.bus.plate | string | License plate |
| current_trip.route.name | string | Route name |
| current_trip.route.start | string | Start point |
| current_trip.route.end | string | End point |
| today_stats.trips_count | integer | Number of trips today |

**Error Responses:**

**403 Forbidden:**
```json
{
  "message": "Access denied. Conductor role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

### 2. Get Current Trip

Retrieve details of the current active trip for the conductor.

**Endpoint:** `GET /conductor/current-trip`

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
  "message": "Current trip retrieved successfully",
  "data": {
    "id": 42,
    "trip_date": "2025-11-20",
    "status": "in_progress",
    "passenger_count": 25,
    "bus": {
      "number": "YBL-001",
      "plate": "ABC-1234"
    },
    "route": {
      "name": "Downtown Express",
      "start": "North Terminal",
      "end": "City Center"
    }
  }
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Trip ID |
| trip_date | date | Date of the trip |
| status | string | Trip status (loading/in_progress) |
| passenger_count | integer | Current passenger count |
| bus.number | string | Bus number |
| bus.plate | string | License plate |
| route.name | string | Route name |
| route.start | string | Start point |
| route.end | string | End point |

**Error Responses:**

**404 Not Found:**
```json
{
  "message": "No active trip found"
}
```

**403 Forbidden:**
```json
{
  "message": "Access denied. Conductor role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

### 3. Update Passenger Count

Update the passenger count for the current active trip.

**Endpoint:** `POST /conductor/update-passenger-count`

**Authentication:** Required

**Request Headers:**
```http
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

**Request Body:**
```json
{
  "passenger_count": 28
}
```

**Request Fields:**
| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| passenger_count | integer | Yes | min:0 | Current number of passengers on the bus |

**Success Response (200 OK):**
```json
{
  "message": "Passenger count updated successfully",
  "data": {
    "passenger_count": 28
  }
}
```

**Error Responses:**

**404 Not Found:**
```json
{
  "message": "No active trip found"
}
```

**403 Forbidden:**
```json
{
  "message": "Access denied. Conductor role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

**422 Unprocessable Entity (Validation Error):**
```json
{
  "message": "The passenger count field is required. (and 1 more error)",
  "errors": {
    "passenger_count": ["The passenger count field is required."]
  }
}
```

**422 Unprocessable Entity (Invalid value):**
```json
{
  "message": "The passenger count must be at least 0.",
  "errors": {
    "passenger_count": ["The passenger count must be at least 0."]
  }
}
```

---

### 4. Get Drop Points

Retrieve all drop points for the current active trip, ordered by sequence.

**Endpoint:** `GET /conductor/drop-points`

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
  "message": "Drop points retrieved successfully",
  "data": [
    {
      "id": 15,
      "trip_id": 42,
      "passenger_id": 8,
      "address": "123 Main Street, Downtown",
      "latitude": 6.11650000,
      "longitude": 25.17170000,
      "sequence_order": 1,
      "status": "requested",
      "requested_at": "2025-11-20T03:50:00.000000Z",
      "forwarded_at": null,
      "confirmed_at": null,
      "completed_at": null,
      "notes": null,
      "created_at": "2025-11-20T03:50:00.000000Z",
      "updated_at": "2025-11-20T03:50:00.000000Z",
      "passenger": {
        "id": 8,
        "name": "John Doe",
        "email": "john.doe@example.com"
      }
    },
    {
      "id": 16,
      "trip_id": 42,
      "passenger_id": 9,
      "address": "456 Oak Avenue",
      "latitude": 6.12000000,
      "longitude": 25.18000000,
      "sequence_order": 2,
      "status": "forwarded",
      "requested_at": "2025-11-20T03:51:00.000000Z",
      "forwarded_at": "2025-11-20T03:52:00.000000Z",
      "confirmed_at": null,
      "completed_at": null,
      "notes": null,
      "created_at": "2025-11-20T03:51:00.000000Z",
      "updated_at": "2025-11-20T03:52:00.000000Z",
      "passenger": {
        "id": 9,
        "name": "Jane Smith",
        "email": "jane.smith@example.com"
      }
    }
  ]
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Drop point ID |
| trip_id | integer | Associated trip ID |
| passenger_id | integer | Requesting passenger's user ID |
| address | string | Drop-off address |
| latitude | decimal | Drop-off latitude |
| longitude | decimal | Drop-off longitude |
| sequence_order | integer | Order in the drop sequence |
| status | enum | Current status (requested/forwarded/confirmed/completed/cancelled) |
| requested_at | timestamp | When request was made |
| forwarded_at | timestamp | When forwarded to driver (null if not yet) |
| confirmed_at | timestamp | When confirmed by driver (null if not yet) |
| completed_at | timestamp | When drop-off completed (null if not yet) |
| notes | string\|null | Additional notes |
| passenger | object | Passenger information |
| passenger.id | integer | Passenger user ID |
| passenger.name | string | Passenger name |
| passenger.email | string | Passenger email |

**Error Responses:**

**404 Not Found:**
```json
{
  "message": "No active trip found"
}
```

**403 Forbidden:**
```json
{
  "message": "Access denied. Conductor role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

### 5. Add Drop Point

Manually add a drop point to the current active trip. Useful for adding drop points for passengers not using the app.

**Endpoint:** `POST /conductor/add-drop-point`

**Authentication:** Required

**Request Headers:**
```http
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

**Request Body:**
```json
{
  "address": "789 Pine Street, Uptown",
  "latitude": 6.12500000,
  "longitude": 25.19000000,
  "passenger_name": "Walk-in Passenger"
}
```

**Request Fields:**
| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| address | string | Yes | - | Drop-off address |
| latitude | decimal | Yes | -90 to 90, max 8 decimals | Drop-off latitude |
| longitude | decimal | Yes | -180 to 180, max 8 decimals | Drop-off longitude |
| passenger_name | string | No | - | Optional name for non-app passengers |

**Success Response (200 OK):**
```json
{
  "message": "Drop point added successfully",
  "data": {
    "id": 17,
    "trip_id": 42,
    "passenger_id": 10,
    "address": "789 Pine Street, Uptown",
    "latitude": 6.12500000,
    "longitude": 25.19000000,
    "sequence_order": 3,
    "status": "requested",
    "requested_at": "2025-11-20T04:00:00.000000Z",
    "forwarded_at": null,
    "confirmed_at": null,
    "completed_at": null,
    "notes": null,
    "created_at": "2025-11-20T04:00:00.000000Z",
    "updated_at": "2025-11-20T04:00:00.000000Z"
  }
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Drop point ID |
| trip_id | integer | Associated trip ID |
| passenger_id | integer | Conductor's user ID (since created manually) |
| address | string | Drop-off address |
| latitude | decimal | Drop-off latitude |
| longitude | decimal | Drop-off longitude |
| sequence_order | integer | Order in the drop sequence (auto-assigned) |
| status | enum | Status (always "requested" for new drop points) |
| requested_at | timestamp | When drop point was added |
| forwarded_at | timestamp | When forwarded to driver (null if not yet) |
| confirmed_at | timestamp | When confirmed by driver (null if not yet) |
| completed_at | timestamp | When drop-off completed (null if not yet) |
| notes | string\|null | Additional notes |

**Error Responses:**

**404 Not Found:**
```json
{
  "message": "No active trip found"
}
```

**403 Forbidden:**
```json
{
  "message": "Access denied. Conductor role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

**422 Unprocessable Entity (Validation Error):**
```json
{
  "message": "The address field is required. (and 2 more errors)",
  "errors": {
    "address": ["The address field is required."],
    "latitude": ["The latitude field is required."],
    "longitude": ["The longitude field is required."]
  }
}
```

**422 Unprocessable Entity (Coordinate out of range):**
```json
{
  "message": "SQLSTATE[22003]: Numeric value out of range",
  "exception": "Coordinate values must fit decimal(10,8) format"
}
```

---

### 6. Forward Drop Point

Forward a drop point request to the driver for confirmation. This changes the status from "requested" to "forwarded".

**Endpoint:** `POST /conductor/forward-drop-point/{dropPoint}`

**Authentication:** Required

**Request Headers:**
```http
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

**URL Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| dropPoint | integer | Yes | Drop point ID (route model binding) |

**Request Body:** None

**Example Request:**
```http
POST /conductor/forward-drop-point/15
```

**Success Response (200 OK):**
```json
{
  "message": "Drop point forwarded to driver",
  "data": {
    "id": 15,
    "trip_id": 42,
    "passenger_id": 8,
    "address": "123 Main Street, Downtown",
    "latitude": 6.11650000,
    "longitude": 25.17170000,
    "sequence_order": 1,
    "status": "forwarded",
    "requested_at": "2025-11-20T03:50:00.000000Z",
    "forwarded_at": null,
    "confirmed_at": null,
    "completed_at": null,
    "notes": null,
    "created_at": "2025-11-20T03:50:00.000000Z",
    "updated_at": "2025-11-20T04:05:00.000000Z"
  }
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Drop point ID |
| trip_id | integer | Associated trip ID |
| passenger_id | integer | Requesting passenger's user ID |
| address | string | Drop-off address |
| latitude | decimal | Drop-off latitude |
| longitude | decimal | Drop-off longitude |
| sequence_order | integer | Order in the drop sequence |
| status | enum | Updated status (now "forwarded") |
| requested_at | timestamp | When request was made |
| forwarded_at | timestamp | When forwarded to driver (may be null) |
| confirmed_at | timestamp | When confirmed by driver (null if not yet) |
| completed_at | timestamp | When drop-off completed (null if not yet) |
| notes | string\|null | Additional notes |

**Error Responses:**

**404 Not Found (No active trip):**
```json
{
  "message": "No active trip found"
}
```

**404 Not Found (Drop point not in current trip):**
```json
{
  "message": "Drop point not found in current trip"
}
```

**403 Forbidden:**
```json
{
  "message": "Access denied. Conductor role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

## Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success - Request completed successfully |
| 400 | Bad Request - Invalid request parameters |
| 401 | Unauthorized - Missing or invalid authentication token |
| 403 | Forbidden - User doesn't have conductor role |
| 404 | Not Found - Resource doesn't exist or no active trip |
| 422 | Unprocessable Entity - Validation failed |
| 500 | Internal Server Error - Server-side error |

---

## Drop Point Status Flow

```
requested → forwarded → confirmed → completed
            ↓
        cancelled
```

**Status Descriptions:**
- **requested**: Passenger has submitted the request (or conductor added manually)
- **forwarded**: Conductor has forwarded to driver for confirmation
- **confirmed**: Driver has acknowledged the drop point
- **completed**: Passenger has been dropped off
- **cancelled**: Request was cancelled

---

## Common Error Scenarios

### 1. No Active Trip
Most conductor endpoints require an active trip (status: `loading` or `in_progress`). If the conductor doesn't have an active trip, these endpoints will return a 404 error.

**Example:**
```json
{
  "message": "No active trip found"
}
```

### 2. Invalid Coordinates
Coordinates must fit `decimal(10,8)` format:
- Maximum 2 digits before decimal point
- Maximum 8 digits after decimal point
- Valid range: Latitude (-90 to 90), Longitude (-180 to 180)

**Example Valid Values:**
```json
{
  "latitude": 6.11640000,
  "longitude": 25.17160000
}
```

**Example Invalid Values:**
```json
{
  "latitude": 125.123,  // Too many digits before decimal
  "longitude": 1.123456789  // Too many decimal places
}
```

### 3. Missing Authentication
All endpoints require a valid bearer token obtained from the login endpoint.

### 4. Wrong Role
All endpoints require the user to have the `conductor` role. Other roles will receive a 403 Forbidden response.

### 5. Drop Point Not in Current Trip
When forwarding a drop point, it must belong to the conductor's current active trip. Otherwise, a 404 error is returned.

---

## Rate Limiting

API requests are rate-limited to prevent abuse:
- **Limit**: 60 requests per minute per user
- **Header**: `X-RateLimit-Remaining` shows remaining requests

**Rate Limit Exceeded Response (429):**
```json
{
  "message": "Too Many Requests"
}
```

---

## Notes

1. All timestamps are in UTC format (ISO 8601)
2. Decimal coordinates use 8 decimal places for precision (~1.1mm accuracy)
3. The `sequence_order` is automatically assigned based on existing drop points when adding new ones
4. Drop points are ordered by `sequence_order` when retrieved
5. Only trips with status `loading` or `in_progress` are considered active
6. When adding a drop point manually, the `passenger_id` is set to the conductor's user ID
7. The `forwarded_at` timestamp may not be automatically updated when forwarding (implementation dependent)

