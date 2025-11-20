# Supervisor API Documentation

## Base URL
```
https://your-domain.com/api/supervisor
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

Retrieve supervisor dashboard with active trips, today's schedules, and bus statistics.

**Endpoint:** `GET /supervisor/dashboard`

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
  "message": "Dashboard data retrieved successfully",
  "data": {
    "active_trips_count": 5,
    "today_schedules_count": 12,
    "active_buses_count": 8,
    "active_trips": [
      {
        "id": 42,
        "trip_date": "2025-11-20",
        "status": "in_progress",
        "passenger_count": 25,
        "actual_departure_time": "08:15",
        "actual_arrival_time": null,
        "bus": {
          "id": 5,
          "bus_number": "YBL-001",
          "license_plate": "ABC-1234"
        },
        "route": {
          "id": 1,
          "route_name": "Downtown Express",
          "start_point": "North Terminal",
          "end_point": "City Center"
        },
        "schedule": {
          "departure_time": "08:00",
          "arrival_time": "09:00",
          "day_of_week": "monday"
        },
        "staff": {
          "driver": {
            "name": "John Driver",
            "phone": "+1234567890"
          },
          "conductor": {
            "name": "Jane Conductor",
            "phone": "+1234567891"
          }
        }
      }
    ],
    "today_schedules": [
      {
        "id": 10,
        "departure_time": "08:00",
        "arrival_time": "09:00",
        "day_of_week": "monday",
        "is_recurring": true,
        "status": "scheduled",
        "bus": {
          "id": 5,
          "bus_number": "YBL-001",
          "license_plate": "ABC-1234",
          "capacity": 50
        },
        "route": {
          "id": 1,
          "route_name": "Downtown Express",
          "start_point": "North Terminal",
          "end_point": "City Center",
          "distance": 15.50,
          "estimated_duration": 45
        }
      }
    ]
  }
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| active_trips_count | integer | Number of active trips today |
| today_schedules_count | integer | Number of scheduled trips for today |
| active_buses_count | integer | Number of active buses |
| active_trips | array | List of active trips with details |
| active_trips[].id | integer | Trip ID |
| active_trips[].trip_date | date | Trip date |
| active_trips[].status | string | Trip status |
| active_trips[].passenger_count | integer | Current passenger count |
| active_trips[].actual_departure_time | string\|null | Actual departure time (HH:mm) |
| active_trips[].actual_arrival_time | string\|null | Actual arrival time (HH:mm) |
| active_trips[].bus | object | Bus information |
| active_trips[].route | object | Route information |
| active_trips[].schedule | object | Schedule information |
| active_trips[].staff | object | Driver and conductor information |
| today_schedules | array | Today's scheduled trips |
| today_schedules[].id | integer | Schedule ID |
| today_schedules[].departure_time | string | Scheduled departure time (HH:mm) |
| today_schedules[].arrival_time | string | Scheduled arrival time (HH:mm) |
| today_schedules[].day_of_week | string | Day of week |
| today_schedules[].is_recurring | boolean | Whether schedule repeats weekly |
| today_schedules[].status | string | Schedule status |
| today_schedules[].bus | object | Bus information |
| today_schedules[].route | object | Route information |

**Error Responses:**

**403 Forbidden:**
```json
{
  "message": "Access denied. Supervisor role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

### 2. Get Buses

Retrieve all buses with their status, current assignments, and latest location information.

**Endpoint:** `GET /supervisor/buses`

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
  "message": "Buses retrieved successfully",
  "data": [
    {
      "id": 5,
      "bus_number": "YBL-001",
      "license_plate": "ABC-1234",
      "model": "Hyundai Universe",
      "capacity": 50,
      "status": "active",
      "current_assignment": {
        "driver": {
          "id": 3,
          "name": "John Driver",
          "phone": "+1234567890"
        },
        "conductor": {
          "id": 4,
          "name": "Jane Conductor",
          "phone": "+1234567891"
        }
      },
      "latest_location": {
        "latitude": 6.11640000,
        "longitude": 25.17160000,
        "recorded_at": "2025-11-20T04:00:00.000000Z"
      }
    }
  ]
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Bus ID |
| bus_number | string | Bus identification number |
| license_plate | string | License plate number |
| model | string | Bus model |
| capacity | integer | Maximum passenger capacity |
| status | string | Bus status (active/inactive/maintenance) |
| current_assignment | object\|null | Current bus assignment |
| current_assignment.driver | object\|null | Assigned driver information |
| current_assignment.driver.id | integer | Driver user ID |
| current_assignment.driver.name | string | Driver name |
| current_assignment.driver.phone | string | Driver phone number |
| current_assignment.conductor | object\|null | Assigned conductor information |
| current_assignment.conductor.id | integer | Conductor user ID |
| current_assignment.conductor.name | string | Conductor name |
| current_assignment.conductor.phone | string | Conductor phone number |
| latest_location | object\|null | Latest GPS location |
| latest_location.latitude | decimal | Latitude |
| latest_location.longitude | decimal | Longitude |
| latest_location.recorded_at | timestamp | When location was recorded |

**Error Responses:**

**403 Forbidden:**
```json
{
  "message": "Access denied. Supervisor role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

### 3. Get Schedules

Retrieve all bus schedules with their associated bus and route information.

**Endpoint:** `GET /supervisor/schedules`

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
  "message": "Schedules retrieved successfully",
  "data": [
    {
      "id": 10,
      "departure_time": "08:00",
      "arrival_time": "09:00",
      "day_of_week": "monday",
      "is_recurring": true,
      "status": "scheduled",
      "bus": {
        "id": 5,
        "bus_number": "YBL-001",
        "license_plate": "ABC-1234",
        "capacity": 50
      },
      "route": {
        "id": 1,
        "route_name": "Downtown Express",
        "start_point": "North Terminal",
        "end_point": "City Center",
        "distance": 15.50,
        "estimated_duration": 45
      }
    }
  ]
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Schedule ID |
| departure_time | string | Scheduled departure time (HH:mm) |
| arrival_time | string | Scheduled arrival time (HH:mm) |
| day_of_week | string | Day of week (lowercase) |
| is_recurring | boolean | Whether schedule repeats weekly |
| status | string | Schedule status |
| bus | object | Bus information |
| bus.id | integer | Bus ID |
| bus.bus_number | string | Bus number |
| bus.license_plate | string | License plate |
| bus.capacity | integer | Bus capacity |
| route | object | Route information |
| route.id | integer | Route ID |
| route.route_name | string | Route name |
| route.start_point | string | Start point |
| route.end_point | string | End point |
| route.distance | decimal | Route distance in km |
| route.estimated_duration | integer | Estimated duration in minutes |

**Error Responses:**

**403 Forbidden:**
```json
{
  "message": "Access denied. Supervisor role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

### 4. Get Bus Locations

Retrieve real-time locations of all buses that have reported locations in the last 5 minutes.

**Endpoint:** `GET /supervisor/bus-locations`

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
      "bus": {
        "id": 5,
        "bus_number": "YBL-001",
        "license_plate": "ABC-1234"
      },
      "trip": {
        "id": 42,
        "status": "in_progress",
        "route_name": "Downtown Express"
      },
      "latitude": 6.11640000,
      "longitude": 25.17160000,
      "speed": 45.50,
      "heading": 180.00,
      "recorded_at": "2025-11-20T04:00:00.000000Z"
    }
  ]
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| bus | object | Bus information |
| bus.id | integer | Bus ID |
| bus.bus_number | string | Bus number |
| bus.license_plate | string | License plate |
| trip | object\|null | Associated trip information |
| trip.id | integer | Trip ID |
| trip.status | string | Trip status |
| trip.route_name | string | Route name |
| latitude | decimal | Current latitude |
| longitude | decimal | Current longitude |
| speed | decimal\|null | Current speed in km/h |
| heading | decimal\|null | Direction in degrees (0-360) |
| recorded_at | timestamp | When location was recorded |

**Error Responses:**

**403 Forbidden:**
```json
{
  "message": "Access denied. Supervisor role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

### 5. Get Transportation Details

Retrieve comprehensive details for a specific trip including bus details, assigned staff, passenger information, route details, and recent locations.

**Endpoint:** `GET /supervisor/transportation-details/{trip}`

**Authentication:** Required

**Request Headers:**
```http
Authorization: Bearer {token}
Accept: application/json
```

**URL Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| trip | integer | Yes | Trip ID (route model binding) |

**Request Body:** None

**Example Request:**
```http
GET /supervisor/transportation-details/42
```

**Success Response (200 OK):**
```json
{
  "message": "Transportation details retrieved successfully",
  "data": {
    "trip": {
      "id": 42,
      "trip_date": "2025-11-20",
      "status": "in_progress",
      "passenger_count": 25,
      "actual_departure_time": "08:15",
      "actual_arrival_time": null,
      "bus": {
        "id": 5,
        "bus_number": "YBL-001",
        "license_plate": "ABC-1234"
      },
      "route": {
        "id": 1,
        "route_name": "Downtown Express",
        "start_point": "North Terminal",
        "end_point": "City Center"
      },
      "schedule": {
        "departure_time": "08:00",
        "arrival_time": "09:00",
        "day_of_week": "monday"
      },
      "staff": {
        "driver": {
          "name": "John Driver",
          "phone": "+1234567890"
        },
        "conductor": {
          "name": "Jane Conductor",
          "phone": "+1234567891"
        }
      }
    },
    "bus_details": {
      "id": 5,
      "bus_number": "YBL-001",
      "license_plate": "ABC-1234",
      "model": "Hyundai Universe",
      "capacity": 50,
      "features": ["GPS", "WiFi", "AC"]
    },
    "assigned_staff": {
      "driver": {
        "id": 3,
        "name": "John Driver",
        "phone": "+1234567890",
        "license_number": "DL-12345"
      },
      "conductor": {
        "id": 4,
        "name": "Jane Conductor",
        "phone": "+1234567891",
        "employee_id": "EMP-001"
      }
    },
    "passenger_info": {
      "current_count": 25,
      "capacity": 50,
      "drop_points": [
        {
          "id": 15,
          "passenger_name": "John Doe",
          "address": "123 Main Street, Downtown",
          "status": "confirmed",
          "requested_at": "2025-11-20T03:50:00.000000Z"
        }
      ]
    },
    "route_details": {
      "route_name": "Downtown Express",
      "start_point": "North Terminal",
      "end_point": "City Center",
      "distance": 15.50,
      "estimated_duration": 45
    },
    "recent_locations": [
      {
        "latitude": 6.11640000,
        "longitude": 25.17160000,
        "speed": 45.50,
        "recorded_at": "2025-11-20T04:00:00.000000Z"
      }
    ]
  }
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| trip | object | Trip information |
| bus_details | object | Detailed bus information |
| bus_details.features | array | Bus features (JSON decoded) |
| assigned_staff | object | Staff assignment details |
| assigned_staff.driver.license_number | string | Driver license number |
| assigned_staff.conductor.employee_id | string | Conductor employee ID |
| passenger_info | object | Passenger information |
| passenger_info.current_count | integer | Current passenger count |
| passenger_info.capacity | integer | Bus capacity |
| passenger_info.drop_points | array | List of drop points |
| route_details | object | Detailed route information |
| recent_locations | array | Last 10 location updates |

**Error Responses:**

**404 Not Found:**
```json
{
  "message": "No query results for model [App\\Models\\Trip] {trip_id}"
}
```

**403 Forbidden:**
```json
{
  "message": "Access denied. Supervisor role required."
}
```

**401 Unauthorized:**
```json
{
  "message": "Unauthenticated."
}
```

---

### 6. Confirm Transportation

Confirm and verify transportation information for a specific trip. Creates a bus log entry for audit purposes.

**Endpoint:** `POST /supervisor/confirm-transportation/{trip}`

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
| trip | integer | Yes | Trip ID (route model binding) |

**Request Body:**
```json
{
  "confirmed_details": {
    "passenger_count": 25,
    "driver_present": true,
    "conductor_present": true,
    "bus_condition": "good"
  },
  "notes": "All passengers accounted for, trip proceeding as scheduled"
}
```

**Request Fields:**
| Field | Type | Required | Constraints | Description |
|-------|------|----------|-------------|-------------|
| confirmed_details | array | Yes | - | Object containing confirmed information |
| notes | string | No | max:500 | Optional notes about the confirmation |

**Success Response (200 OK):**
```json
{
  "message": "Transportation information confirmed successfully",
  "data": {
    "trip_id": 42,
    "confirmed_by": "Supervisor Name",
    "confirmed_at": "2025-11-20T04:15:00.000000Z"
  }
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| trip_id | integer | Trip ID that was confirmed |
| confirmed_by | string | Name of supervisor who confirmed |
| confirmed_at | timestamp | When confirmation was made |

**Error Responses:**

**404 Not Found:**
```json
{
  "message": "No query results for model [App\\Models\\Trip] {trip_id}"
}
```

**403 Forbidden:**
```json
{
  "message": "Access denied. Supervisor role required."
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
  "message": "The confirmed details field is required.",
  "errors": {
    "confirmed_details": ["The confirmed details field is required."]
  }
}
```

**422 Unprocessable Entity (Notes too long):**
```json
{
  "message": "The notes must not be greater than 500 characters.",
  "errors": {
    "notes": ["The notes must not be greater than 500 characters."]
  }
}
```

---

### 7. Get Trips

Retrieve all trips with optional filtering by date, status, or bus ID.

**Endpoint:** `GET /supervisor/trips`

**Authentication:** Required

**Request Headers:**
```http
Authorization: Bearer {token}
Accept: application/json
```

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| date | date | No | Filter trips by specific date (YYYY-MM-DD) |
| status | string | No | Filter trips by status (scheduled/in_progress/completed/cancelled) |
| bus_id | integer | No | Filter trips by bus ID |

**Example Requests:**
```http
GET /supervisor/trips
GET /supervisor/trips?date=2025-11-20
GET /supervisor/trips?status=in_progress
GET /supervisor/trips?date=2025-11-20&status=completed&bus_id=5
```

**Success Response (200 OK):**
```json
{
  "message": "Trips retrieved successfully",
  "data": [
    {
      "id": 42,
      "trip_date": "2025-11-20",
      "status": "in_progress",
      "passenger_count": 25,
      "actual_departure_time": "08:15",
      "actual_arrival_time": null,
      "bus": {
        "id": 5,
        "bus_number": "YBL-001",
        "license_plate": "ABC-1234"
      },
      "route": {
        "id": 1,
        "route_name": "Downtown Express",
        "start_point": "North Terminal",
        "end_point": "City Center"
      },
      "schedule": {
        "departure_time": "08:00",
        "arrival_time": "09:00",
        "day_of_week": "monday"
      },
      "staff": {
        "driver": {
          "name": "John Driver",
          "phone": "+1234567890"
        },
        "conductor": {
          "name": "Jane Conductor",
          "phone": "+1234567891"
        }
      }
    }
  ]
}
```

**Response Fields:**
| Field | Type | Description |
|-------|------|-------------|
| id | integer | Trip ID |
| trip_date | date | Trip date |
| status | string | Trip status |
| passenger_count | integer | Passenger count |
| actual_departure_time | string\|null | Actual departure time (HH:mm) |
| actual_arrival_time | string\|null | Actual arrival time (HH:mm) |
| bus | object | Bus information |
| route | object | Route information |
| schedule | object | Schedule information |
| staff | object | Driver and conductor information |

**Error Responses:**

**403 Forbidden:**
```json
{
  "message": "Access denied. Supervisor role required."
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
| 403 | Forbidden - User doesn't have supervisor role |
| 404 | Not Found - Resource doesn't exist |
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
- **loading**: Passengers are boarding
- **in_progress**: Trip has started and is ongoing
- **completed**: Trip has finished
- **cancelled**: Trip was cancelled

---

## Common Error Scenarios

### 1. Missing Authentication
All endpoints require a valid bearer token obtained from the login endpoint.

### 2. Wrong Role
All endpoints require the user to have the `supervisor` role. Other roles will receive a 403 Forbidden response.

**Example:**
```json
{
  "message": "Access denied. Supervisor role required."
}
```

### 3. Trip Not Found
When accessing trip-specific endpoints, if the trip ID doesn't exist, a 404 error is returned.

**Example:**
```json
{
  "message": "No query results for model [App\\Models\\Trip] 999"
}
```

### 4. Validation Errors
When confirming transportation, the `confirmed_details` field must be an array and `notes` must not exceed 500 characters.

**Example:**
```json
{
  "message": "The confirmed details field is required.",
  "errors": {
    "confirmed_details": ["The confirmed details field is required."]
  }
}
```

### 5. No Recent Bus Locations
The `getBusLocations` endpoint only returns buses that have reported locations in the last 5 minutes. If no buses have reported recently, an empty array is returned.

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
2. The dashboard shows only today's active trips and schedules
3. Bus locations are filtered to show only locations from the last 5 minutes
4. The `getTrips` endpoint supports multiple query parameters that can be combined
5. When confirming transportation, a bus log entry is automatically created for audit purposes
6. The `confirmed_details` field in `confirmTransportation` can contain any key-value pairs as needed
7. Recent locations in transportation details are limited to the last 10 location updates
8. Bus features are stored as JSON and automatically decoded in the response
9. All endpoints return data sorted appropriately (buses by number, schedules by day and time, trips by date)

