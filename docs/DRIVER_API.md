# Driver API Documentation

## Base URL
```
https://your-domain.com/api/driver
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

Retrieve driver dashboard with current trip information, today's trip count, and assigned bus details.

**Endpoint:** `GET /driver/dashboard`

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
  "message": "Driver dashboard retrieved successfully",
  "data": {
    "current_trip": {
      "id": 42,
      "trip_date": "2025-11-20",
      "status": "in_progress",
      "passenger_count": 25,
      "actual_departure_time": "08:15",
      "actual_arrival_time": null,
      "route": {
        "route_name": "Downtown Express",
        "start_point": "North Terminal",
        "end_point": "City Center"
      }
    },
    "today_trips": 3,
    "assigned_bus": {
      "id": 5,
      "bus_number": "YBL-001",
      "license_plate": "ABC-1234"
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
| current_trip.status | string | Trip status (scheduled/in_progress) |
| current_trip.passenger_count | integer | Current passenger count |
| current_trip.actual_departure_time | string\|null | Actual departure time (HH:mm format) |
| current_trip.actual_arrival_time | string\|null | Actual arrival time (HH:mm format, null if not completed) |
| current_trip.route.route_name | string | Route name |
| current_trip.route.start_point | string | Start point |
| current_trip.route.end_point | string | End point |
| today_trips | integer | Number of trips driven today |
| assigned_bus | object\|null | Currently assigned bus (null if not assigned) |
| assigned_bus.id | integer | Bus ID |
| assigned_bus.bus_number | string | Bus number |
| assigned_bus.license_plate | string | License plate |

**Error Responses:**

**403 Forbidden:**
```json
{
  "message": "Access denied. Driver role required."
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

Retrieve details of the current active trip for the driver.

**Endpoint:** `GET /driver/current-trip`

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
    "actual_departure_time": "08:15",
    "actual_arrival_time": null,
    "route": {
      "route_name": "Downtown Express",
      "start_point": "North Terminal",
      "end_point": "City Center"
    }
  }
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Trip ID |
| trip_date | date | Date of the trip |
| status | string | Trip status (scheduled/in_progress) |
| passenger_count | integer | Current passenger count |
| actual_departure_time | string\|null | Actual departure time (HH:mm format) |
| actual_arrival_time | string\|null | Actual arrival time (HH:mm format, null if not completed) |
| route.route_name | string | Route name |
| route.start_point | string | Start point |
| route.end_point | string | End point |

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
  "message": "Access denied. Driver role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

### 3. Start Trip

Start a scheduled trip. Changes trip status from "scheduled" to "in_progress" and records the departure location.

**Endpoint:** `POST /driver/start-trip`

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
  "latitude": 6.11640000,
  "longitude": 25.17160000
}
```

**Request Fields:**
| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| latitude | decimal | Yes | -90 to 90, max 8 decimals | Departure latitude |
| longitude | decimal | Yes | -180 to 180, max 8 decimals | Departure longitude |

**Success Response (200 OK):**
```json
{
  "message": "Trip started successfully",
  "data": {
    "id": 42,
    "trip_date": "2025-11-20",
    "status": "in_progress",
    "passenger_count": 0,
    "actual_departure_time": "08:15",
    "actual_arrival_time": null,
    "route": {
      "route_name": "Downtown Express",
      "start_point": "North Terminal",
      "end_point": "City Center"
    }
  }
}
```

**Error Responses:**

**404 Not Found:**
```json
{
  "message": "No scheduled trip found"
}
```

**400 Bad Request (Trip already started):**
```json
{
  "message": "Trip has already been started or completed"
}
```

**403 Forbidden:**
```json
{
  "message": "Access denied. Driver role required."
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
  "message": "The latitude field is required. (and 1 more error)",
  "errors": {
    "latitude": ["The latitude field is required."],
    "longitude": ["The longitude field is required."]
  }
}
```

---

### 4. Update Trip Location

Update the real-time GPS location of the bus during an active trip. This creates a new bus location record.

**Endpoint:** `POST /driver/update-trip-location`

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
  "latitude": 6.12000000,
  "longitude": 25.18000000,
  "speed": 45.50,
  "heading": 180.00
}
```

**Request Fields:**
| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| latitude | decimal | Yes | -90 to 90, max 8 decimals | Current latitude |
| longitude | decimal | Yes | -180 to 180, max 8 decimals | Current longitude |
| speed | decimal | No | - | Current speed in km/h |
| heading | decimal | No | 0-360 | Direction in degrees |

**Success Response (200 OK):**
```json
{
  "message": "Location updated successfully"
}
```

**Error Responses:**

**404 Not Found:**
```json
{
  "message": "No active trip in progress"
}
```

**403 Forbidden:**
```json
{
  "message": "Access denied. Driver role required."
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
  "message": "The latitude field is required. (and 1 more error)",
  "errors": {
    "latitude": ["The latitude field is required."],
    "longitude": ["The longitude field is required."]
  }
}
```

---

### 5. Get Today Schedule

Retrieve the scheduled trip for today based on the driver's bus assignment and day of week.

**Endpoint:** `GET /driver/today-schedule`

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
  "message": "Today schedule retrieved successfully",
  "data": {
    "schedule": {
      "id": 10,
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
      "bus": {
        "id": 5,
        "bus_number": "YBL-001",
        "license_plate": "ABC-1234",
        "capacity": 50,
        "model": "Hyundai Universe",
        "status": "active"
      },
      "route": {
        "id": 1,
        "route_name": "Downtown Express",
        "start_point": "North Terminal",
        "end_point": "City Center"
      }
    }
  }
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| schedule | object | Today's schedule information |
| schedule.id | integer | Schedule ID |
| schedule.bus_id | integer | Bus ID |
| schedule.route_id | integer | Route ID |
| schedule.departure_time | time | Scheduled departure time |
| schedule.arrival_time | time | Scheduled arrival time |
| schedule.day_of_week | string | Day of week (lowercase) |
| schedule.is_recurring | boolean | Whether schedule repeats weekly |
| schedule.effective_date | date | Schedule effective start date |
| schedule.end_date | date\|null | Schedule end date (null if ongoing) |
| schedule.status | string | Schedule status |
| schedule.bus | object | Bus information |
| schedule.route | object | Route information |

**Error Responses:**

**404 Not Found (No assignment):**
```json
{
  "message": "No bus assignment found for today"
}
```

**404 Not Found (No schedule):**
```json
{
  "message": "No scheduled trip found for today"
}
```

**403 Forbidden:**
```json
{
  "message": "Access denied. Driver role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

### 6. Create Trip

Manually create a new trip. Useful for ad-hoc trips not in the regular schedule.

**Endpoint:** `POST /driver/create-trip`

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
  "schedule_id": 10,
  "bus_id": 5
}
```

**Request Fields:**
| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| schedule_id | integer | Yes | exists:schedules,id | Schedule ID to base trip on |
| bus_id | integer | Yes | exists:buses,id | Bus assignment ID |

**Success Response (200 OK):**
```json
{
  "message": "Trip created successfully",
  "data": {
    "id": 43,
    "schedule_id": 10,
    "bus_assignment_id": 5,
    "trip_date": "2025-11-20",
    "actual_departure_time": null,
    "actual_arrival_time": null,
    "passenger_count": 0,
    "start_latitude": null,
    "start_longitude": null,
    "end_latitude": null,
    "end_longitude": null,
    "status": "loading",
    "notes": null,
    "created_at": "2025-11-20T04:00:00.000000Z",
    "updated_at": "2025-11-20T04:00:00.000000Z"
  }
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Trip ID |
| schedule_id | integer | Schedule ID |
| bus_assignment_id | integer | Bus assignment ID |
| trip_date | date | Trip date (today) |
| actual_departure_time | datetime\|null | Actual departure time |
| actual_arrival_time | datetime\|null | Actual arrival time |
| passenger_count | integer | Passenger count (starts at 0) |
| start_latitude | decimal\|null | Start latitude |
| start_longitude | decimal\|null | Start longitude |
| end_latitude | decimal\|null | End latitude |
| end_longitude | decimal\|null | End longitude |
| status | string | Trip status (created as "loading") |
| notes | string\|null | Trip notes |

**Error Responses:**

**403 Forbidden:**
```json
{
  "message": "Access denied. Driver role required."
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
  "message": "The schedule id field is required. (and 1 more error)",
  "errors": {
    "schedule_id": ["The schedule id field is required."],
    "bus_id": ["The bus id field is required."]
  }
}
```

**422 Unprocessable Entity (Invalid ID):**
```json
{
  "message": "The selected schedule id is invalid.",
  "errors": {
    "schedule_id": ["The selected schedule id is invalid."]
  }
}
```

---

### 7. Complete Trip

Mark the current trip as completed. Changes trip status from "in_progress" to "completed" and records the arrival location.

**Endpoint:** `POST /driver/complete-trip`

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
  "latitude": 6.13000000,
  "longitude": 25.20000000
}
```

**Request Fields:**
| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| latitude | decimal | Yes | -90 to 90, max 8 decimals | Arrival latitude |
| longitude | decimal | Yes | -180 to 180, max 8 decimals | Arrival longitude |

**Success Response (200 OK):**
```json
{
  "message": "Trip completed successfully",
  "data": {
    "id": 42,
    "trip_date": "2025-11-20",
    "status": "completed",
    "passenger_count": 25,
    "actual_departure_time": "08:15",
    "actual_arrival_time": "09:30",
    "route": {
      "route_name": "Downtown Express",
      "start_point": "North Terminal",
      "end_point": "City Center"
    }
  }
}
```

**Error Responses:**

**404 Not Found:**
```json
{
  "message": "No active trip in progress"
}
```

**403 Forbidden:**
```json
{
  "message": "Access denied. Driver role required."
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
  "message": "The latitude field is required. (and 1 more error)",
  "errors": {
    "latitude": ["The latitude field is required."],
    "longitude": ["The longitude field is required."]
  }
}
```

---

### 8. Get Drop Points

Retrieve all drop points for the current active trip, ordered by sequence. Shows passenger names and drop point details.

**Endpoint:** `GET /driver/drop-points`

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
      "passenger_name": "John Doe",
      "address": "123 Main Street, Downtown",
      "latitude": 6.11650000,
      "longitude": 25.17170000,
      "sequence_order": 1,
      "status": "forwarded",
      "requested_at": "2025-11-20T03:50:00.000000Z"
    },
    {
      "id": 16,
      "passenger_name": "Jane Smith",
      "address": "456 Oak Avenue",
      "latitude": 6.12000000,
      "longitude": 25.18000000,
      "sequence_order": 2,
      "status": "confirmed",
      "requested_at": "2025-11-20T03:51:00.000000Z"
    }
  ]
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Drop point ID |
| passenger_name | string | Name of the passenger |
| address | string | Drop-off address |
| latitude | decimal | Drop-off latitude |
| longitude | decimal | Drop-off longitude |
| sequence_order | integer | Order in the drop sequence |
| status | enum | Current status (requested/forwarded/confirmed/completed/cancelled) |
| requested_at | timestamp | When request was made |

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
  "message": "Access denied. Driver role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

### 9. Confirm Drop Point

Confirm a drop point that has been forwarded by the conductor. Changes status from "forwarded" to "confirmed".

**Endpoint:** `POST /driver/confirm-drop-point/{dropPoint}`

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
POST /driver/confirm-drop-point/15
```

**Success Response (200 OK):**
```json
{
  "message": "Drop point confirmed successfully",
  "data": {
    "id": 15,
    "status": "confirmed",
    "confirmed_at": "2025-11-20T04:10:00.000000Z"
  }
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Drop point ID |
| status | enum | Updated status (now "confirmed") |
| confirmed_at | timestamp | When drop point was confirmed |

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

**400 Bad Request (Not ready for confirmation):**
```json
{
  "message": "Drop point is not ready for confirmation"
}
```

**403 Forbidden:**
```json
{
  "message": "Access denied. Driver role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

### 10. Report Issue

Report an issue (mechanical, accident, or other) related to the bus or trip.

**Endpoint:** `POST /driver/issues`

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
  "type": "mechanical",
  "description": "Engine overheating, temperature gauge showing high",
  "bus_id": 5
}
```

**Request Fields:**
| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| type | string | Yes | in:mechanical,accident,other | Type of issue |
| description | string | Yes | - | Detailed description of the issue |
| bus_id | integer | No | exists:buses,id | Bus ID (optional) |

**Success Response (201 Created):**
```json
{
  "message": "Issue reported successfully",
  "data": {
    "id": 7,
    "driver_id": 3,
    "bus_id": 5,
    "type": "mechanical",
    "description": "Engine overheating, temperature gauge showing high",
    "status": "open",
    "created_at": "2025-11-20T04:15:00.000000Z",
    "updated_at": "2025-11-20T04:15:00.000000Z"
  }
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Issue ID |
| driver_id | integer | Driver's user ID |
| bus_id | integer\|null | Bus ID (if provided) |
| type | string | Issue type (mechanical/accident/other) |
| description | string | Issue description |
| status | string | Issue status (always "open" when created) |
| created_at | timestamp | When issue was reported |
| updated_at | timestamp | Last update time |

**Error Responses:**

**403 Forbidden:**
```json
{
  "message": "Access denied. Driver role required."
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
  "message": "The type field is required. (and 1 more error)",
  "errors": {
    "type": ["The type field is required."],
    "description": ["The description field is required."]
  }
}
```

**422 Unprocessable Entity (Invalid type):**
```json
{
  "message": "The selected type is invalid.",
  "errors": {
    "type": ["The selected type is invalid."]
  }
}
```

**422 Unprocessable Entity (Invalid bus_id):**
```json
{
  "message": "The selected bus id is invalid.",
  "errors": {
    "bus_id": ["The selected bus id is invalid."]
  }
}
```

---

## Status Codes

| Code | Meaning |
|------|---------|
| 200 | Success - Request completed successfully |
| 201 | Created - Resource created successfully |
| 400 | Bad Request - Invalid request parameters |
| 401 | Unauthorized - Missing or invalid authentication token |
| 403 | Forbidden - User doesn't have driver role |
| 404 | Not Found - Resource doesn't exist or no active trip |
| 422 | Unprocessable Entity - Validation failed |
| 500 | Internal Server Error - Server-side error |

---

## Trip Status Flow

```
scheduled → loading → in_progress → completed
            ↓
        cancelled
```

**Status Descriptions:**
- **scheduled**: Trip is scheduled but not yet started
- **loading**: Passengers are boarding (trip created manually)
- **in_progress**: Trip has started and is ongoing
- **completed**: Trip has finished
- **cancelled**: Trip was cancelled

---

## Drop Point Status Flow

```
requested → forwarded → confirmed → completed
            ↓
        cancelled
```

**Status Descriptions:**
- **requested**: Passenger has submitted the request
- **forwarded**: Conductor has forwarded to driver for confirmation
- **confirmed**: Driver has acknowledged the drop point
- **completed**: Passenger has been dropped off
- **cancelled**: Request was cancelled

---

## Common Error Scenarios

### 1. No Active Trip
Many driver endpoints require an active trip (status: `scheduled` or `in_progress`). If the driver doesn't have an active trip, these endpoints will return a 404 error.

**Example:**
```json
{
  "message": "No active trip found"
}
```

### 2. Trip Already Started
When trying to start a trip, it must be in "scheduled" status. If it's already started or completed, a 400 error is returned.

**Example:**
```json
{
  "message": "Trip has already been started or completed"
}
```

### 3. Invalid Coordinates
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

### 4. Missing Authentication
All endpoints require a valid bearer token obtained from the login endpoint.

### 5. Wrong Role
All endpoints require the user to have the `driver` role. Other roles will receive a 403 Forbidden response.

### 6. Drop Point Not Ready for Confirmation
Drop points can only be confirmed if they are in "forwarded" status. If the status is not "forwarded", a 400 error is returned.

**Example:**
```json
{
  "message": "Drop point is not ready for confirmation"
}
```

### 7. No Bus Assignment
The `getTodaySchedule` endpoint requires an active bus assignment for today. If no assignment exists, a 404 error is returned.

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
3. The `currentTrip` is determined by the driver's assigned bus and today's date
4. Only trips with status `scheduled` or `in_progress` are considered active
5. When starting a trip, the status changes from "scheduled" to "in_progress" and `actual_departure_time` is set
6. When completing a trip, the status changes to "completed" and `actual_arrival_time` is set
7. Location updates create new `BusLocation` records for tracking purposes
8. Drop points are ordered by `sequence_order` when retrieved
9. Drop points must be in "forwarded" status before they can be confirmed by the driver
10. The `bus_id` parameter in `createTrip` actually refers to `bus_assignment_id` in the database

