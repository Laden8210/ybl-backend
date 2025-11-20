# Passenger API Documentation

## Base URL
```
https://your-domain.com/api/passenger
```

## Authentication
All endpoints require authentication using Laravel Sanctum bearer token.

**Header:**
```
Authorization: Bearer {token}
```

---

## Endpoints

### 1. Get Routes

Retrieve all active routes available for passengers.

**Endpoint:** `GET /passenger/routes`

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
  "message": "Routes retrieved successfully",
  "data": [
    {
      "id": 1,
      "route_name": "Downtown Express",
      "description": "Fast route to downtown area",
      "start_point": "North Terminal",
      "end_point": "City Center",
      "distance": 15.50,
      "estimated_duration": 45,
      "waypoints": ["Station A", "Station B", "Station C"],
      "is_active": true,
      "created_at": "2025-11-19T10:00:00.000000Z",
      "updated_at": "2025-11-19T10:00:00.000000Z"
    }
  ]
}
```

**Error Responses:**

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

**500 Internal Server Error:**
```json
{
  "message": "Server error occurred",
  "exception": "Error details..."
}
```

---

### 2. Get Schedules

Retrieve bus schedules, optionally filtered by route.

**Endpoint:** `GET /passenger/schedules`

**Authentication:** Required

**Request Headers:**
```http
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| route_id | integer | No | Filter schedules by specific route ID |

**Example Request:**
```http
GET /passenger/schedules?route_id=1
```

**Success Response (200 OK):**
```json
{
  "message": "Schedules retrieved successfully",
  "data": [
    {
      "id": 1,
      "bus_id": 5,
      "route_id": 1,
      "departure_time": "08:00:00",
      "arrival_time": "09:00:00",
      "day_of_week": "monday",
      "is_recurring": true,
      "effective_date": "2025-11-01",
      "end_date": null,
      "status": "scheduled",
      "created_at": "2025-11-19T10:00:00.000000Z",
      "updated_at": "2025-11-19T10:00:00.000000Z",
      "route": {
        "id": 1,
        "route_name": "Downtown Express",
        "start_point": "North Terminal",
        "end_point": "City Center"
      },
      "bus": {
        "id": 5,
        "bus_number": "YBL-001",
        "license_plate": "ABC-1234",
        "capacity": 50,
        "model": "Hyundai Universe",
        "status": "active"
      }
    }
  ]
}
```

**Error Responses:**

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

**422 Unprocessable Entity (Invalid route_id):**
```json
{
  "message": "The selected route id is invalid.",
  "errors": {
    "route_id": ["The selected route id is invalid."]
  }
}
```

---

### 3. Get Bus Locations

Retrieve real-time locations of all active buses.

**Endpoint:** `GET /passenger/bus-locations`

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
  "message": "Bus locations retrieved successfully",
  "data": [
    {
      "trip_id": 42,
      "bus_number": "YBL-001",
      "route_name": "Downtown Express",
      "latitude": 6.11640000,
      "longitude": 25.17160000,
      "speed": 45.50,
      "heading": 180.00,
      "last_updated": "2025-11-20T03:45:00.000000Z"
    },
    {
      "trip_id": 43,
      "bus_number": "YBL-002",
      "route_name": "Airport Shuttle",
      "latitude": 6.12000000,
      "longitude": 25.18000000,
      "speed": 60.00,
      "heading": 90.00,
      "last_updated": "2025-11-20T03:44:30.000000Z"
    }
  ]
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| trip_id | integer | ID of the active trip |
| bus_number | string | Bus identification number |
| route_name | string | Name of the route |
| latitude | decimal(10,8) | Current latitude (-90 to 90) |
| longitude | decimal(10,8) | Current longitude (-180 to 180) |
| speed | decimal(6,2) | Current speed in km/h |
| heading | decimal(5,2) | Direction in degrees (0-360) |
| last_updated | timestamp | Last location update time |

**Error Responses:**

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

### 4. Request Drop Point

Submit a request to be dropped off at a specific location during an active trip.

**Endpoint:** `POST /passenger/request-drop-point`

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
  "trip_id": 42,
  "address": "123 Main Street, Downtown",
  "latitude": 6.11650000,
  "longitude": 25.17170000
}
```

**Request Fields:**
| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| trip_id | integer | Yes | exists:trips,id | ID of the active trip |
| address | string | Yes | - | Drop-off address |
| latitude | decimal | Yes | -90 to 90, max 8 decimals | Drop-off latitude |
| longitude | decimal | Yes | -180 to 180, max 8 decimals | Drop-off longitude |

**Success Response (200 OK):**
```json
{
  "message": "Drop point requested successfully",
  "data": {
    "id": 15,
    "trip_id": 42,
    "passenger_id": 8,
    "address": "123 Main Street, Downtown",
    "latitude": 6.11650000,
    "longitude": 25.17170000,
    "sequence_order": 3,
    "status": "requested",
    "requested_at": "2025-11-20T03:50:00.000000Z",
    "forwarded_at": null,
    "confirmed_at": null,
    "completed_at": null,
    "notes": null,
    "created_at": "2025-11-20T03:50:00.000000Z",
    "updated_at": "2025-11-20T03:50:00.000000Z"
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
| status | enum | Current status (requested/forwarded/confirmed/completed/cancelled) |
| requested_at | timestamp | When request was made |
| forwarded_at | timestamp | When forwarded to driver (null if not yet) |
| confirmed_at | timestamp | When confirmed by driver (null if not yet) |
| completed_at | timestamp | When drop-off completed (null if not yet) |

**Error Responses:**

**400 Bad Request (Trip not active):**
```json
{
  "message": "Trip is not active"
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
  "message": "The trip id field is required. (and 2 more errors)",
  "errors": {
    "trip_id": ["The trip id field is required."],
    "address": ["The address field is required."],
    "latitude": ["The latitude field is required."]
  }
}
```

**422 Unprocessable Entity (Invalid trip_id):**
```json
{
  "message": "The selected trip id is invalid.",
  "errors": {
    "trip_id": ["The selected trip id is invalid."]
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

### 5. Get My Requests

Retrieve all drop point requests made by the authenticated passenger.

**Endpoint:** `GET /passenger/my-requests`

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
  "message": "My requests retrieved successfully",
  "data": [
    {
      "id": 15,
      "trip_id": 42,
      "passenger_id": 8,
      "address": "123 Main Street, Downtown",
      "latitude": 6.11650000,
      "longitude": 25.17170000,
      "sequence_order": 3,
      "status": "confirmed",
      "requested_at": "2025-11-20T03:50:00.000000Z",
      "forwarded_at": "2025-11-20T03:51:00.000000Z",
      "confirmed_at": "2025-11-20T03:52:00.000000Z",
      "completed_at": null,
      "notes": null,
      "created_at": "2025-11-20T03:50:00.000000Z",
      "updated_at": "2025-11-20T03:52:00.000000Z",
      "trip": {
        "id": 42,
        "trip_date": "2025-11-20",
        "status": "in_progress",
        "route": {
          "id": 1,
          "route_name": "Downtown Express"
        },
        "bus": {
          "id": 5,
          "bus_number": "YBL-001"
        }
      }
    }
  ]
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

## Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success - Request completed successfully |
| 400 | Bad Request - Invalid request parameters |
| 401 | Unauthorized - Missing or invalid authentication token |
| 403 | Forbidden - User doesn't have permission |
| 404 | Not Found - Resource doesn't exist |
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
- **requested**: Passenger has submitted the request
- **forwarded**: Conductor has forwarded to driver
- **confirmed**: Driver has acknowledged the drop point
- **completed**: Passenger has been dropped off
- **cancelled**: Request was cancelled

---

## Common Error Scenarios

### 1. Invalid Coordinates
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

### 2. Trip Not Active
Drop points can only be requested for trips with status `in_progress` or `loading`.

### 3. Missing Authentication
All endpoints require a valid bearer token obtained from the login endpoint.

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
3. The `sequence_order` is automatically assigned based on existing drop points
4. Drop point requests are immediately visible to conductors
5. Conductors must forward requests to drivers for confirmation
