# Data Dictionary
## Bus Dropping Point System

This document provides a comprehensive data dictionary for all database tables used in the Bus Dropping Point System. Each table includes field names, data types, sizes, and descriptions.

---

## Table 1. Users Data

| Field Name | Data Type | Size | Description |
|------------|-----------|------|-------------|
| id | BIGINT UNSIGNED | 20 | Unique identifier of user |
| name | VARCHAR | 255 | Full name of user |
| email | VARCHAR | 255 | Login email of user (unique) |
| email_verified_at | TIMESTAMP | - | Timestamp when email was verified |
| password | VARCHAR | 255 | Encrypted password of user |
| role | ENUM | - | Role of user (admin, supervisor, driver, conductor, passenger) |
| phone | VARCHAR | 255 | Phone number of user |
| address | TEXT | - | Address of user |
| license_number | VARCHAR | 255 | License number (for drivers only) |
| employee_id | VARCHAR | 255 | Employee ID (for staff: supervisor, driver, conductor) |
| is_active | BOOLEAN | 1 | Status of user account (true = active, false = inactive) |
| remember_token | VARCHAR | 100 | Token for "remember me" functionality |
| created_at | TIMESTAMP | - | Timestamp when record was created |
| updated_at | TIMESTAMP | - | Timestamp when record was last updated |

---

## Table 2. Buses Data

| Field Name | Data Type | Size | Description |
|------------|-----------|------|-------------|
| id | BIGINT UNSIGNED | 20 | Unique identifier of bus |
| bus_number | VARCHAR | 255 | Bus identification number (unique) |
| license_plate | VARCHAR | 255 | License plate number of bus (unique) |
| capacity | INT | 11 | Maximum passenger capacity of bus |
| model | VARCHAR | 255 | Model of bus |
| color | VARCHAR | 255 | Color of bus |
| status | ENUM | - | Status of bus (active, maintenance, inactive) |
| features | TEXT | - | Features of bus (AC, WiFi, etc.) |
| created_at | TIMESTAMP | - | Timestamp when record was created |
| updated_at | TIMESTAMP | - | Timestamp when record was last updated |

---

## Table 3. Bus Assignments Data

| Field Name | Data Type | Size | Description |
|------------|-----------|------|-------------|
| id | BIGINT UNSIGNED | 20 | Unique identifier of bus assignment |
| bus_id | BIGINT UNSIGNED | 20 | Foreign key reference to buses table |
| driver_id | BIGINT UNSIGNED | 20 | Foreign key reference to users table (driver) |
| conductor_id | BIGINT UNSIGNED | 20 | Foreign key reference to users table (conductor) |
| assignment_date | DATE | - | Date of bus assignment |
| status | ENUM | - | Status of assignment (active, completed, cancelled) |
| notes | TEXT | - | Additional notes about assignment |
| created_at | TIMESTAMP | - | Timestamp when record was created |
| updated_at | TIMESTAMP | - | Timestamp when record was last updated |

---

## Table 4. Routes Data

| Field Name | Data Type | Size | Description |
|------------|-----------|------|-------------|
| id | BIGINT UNSIGNED | 20 | Unique identifier of route |
| route_name | VARCHAR | 255 | Name of route |
| description | TEXT | - | Description of route |
| start_point | VARCHAR | 255 | Starting point of route |
| end_point | VARCHAR | 255 | Ending point of route |
| start_latitude | DECIMAL | 10,8 | Latitude coordinate of start point |
| start_longitude | DECIMAL | 10,8 | Longitude coordinate of start point |
| end_latitude | DECIMAL | 10,8 | Latitude coordinate of end point |
| end_longitude | DECIMAL | 10,8 | Longitude coordinate of end point |
| distance | DECIMAL | 8,2 | Distance of route in kilometers |
| estimated_duration | INT | 11 | Estimated duration of route in minutes |
| waypoints | TEXT | - | JSON array of waypoints along route |
| is_active | BOOLEAN | 1 | Status of route (true = active, false = inactive) |
| created_at | TIMESTAMP | - | Timestamp when record was created |
| updated_at | TIMESTAMP | - | Timestamp when record was last updated |

---

## Table 5. Schedules Data

| Field Name | Data Type | Size | Description |
|------------|-----------|------|-------------|
| id | BIGINT UNSIGNED | 20 | Unique identifier of schedule |
| bus_id | BIGINT UNSIGNED | 20 | Foreign key reference to buses table |
| route_id | BIGINT UNSIGNED | 20 | Foreign key reference to routes table |
| departure_time | TIME | - | Scheduled departure time |
| arrival_time | TIME | - | Scheduled arrival time |
| day_of_week | ENUM | - | Day of week (monday, tuesday, wednesday, thursday, friday, saturday, sunday) |
| is_recurring | BOOLEAN | 1 | Whether schedule repeats weekly (true = recurring, false = one-time) |
| effective_date | DATE | - | Date when schedule becomes effective |
| end_date | DATE | - | Date when schedule ends (null if ongoing) |
| status | ENUM | - | Status of schedule (scheduled, departed, in_progress, completed, cancelled) |
| created_at | TIMESTAMP | - | Timestamp when record was created |
| updated_at | TIMESTAMP | - | Timestamp when record was last updated |

---

## Table 6. Trips Data

| Field Name | Data Type | Size | Description |
|------------|-----------|------|-------------|
| id | BIGINT UNSIGNED | 20 | Unique identifier of trip |
| schedule_id | BIGINT UNSIGNED | 20 | Foreign key reference to schedules table |
| bus_assignment_id | BIGINT UNSIGNED | 20 | Foreign key reference to bus_assignments table |
| trip_date | DATE | - | Date of trip |
| actual_departure_time | TIME | - | Actual time when trip started |
| actual_arrival_time | TIME | - | Actual time when trip ended |
| passenger_count | INT | 11 | Number of passengers on trip |
| start_latitude | DECIMAL | 10,8 | Latitude coordinate of trip start location |
| start_longitude | DECIMAL | 10,8 | Longitude coordinate of trip start location |
| end_latitude | DECIMAL | 10,8 | Latitude coordinate of trip end location |
| end_longitude | DECIMAL | 10,8 | Longitude coordinate of trip end location |
| status | ENUM | - | Status of trip (scheduled, in_progress, completed, cancelled) |
| notes | TEXT | - | Additional notes about trip |
| created_at | TIMESTAMP | - | Timestamp when record was created |
| updated_at | TIMESTAMP | - | Timestamp when record was last updated |

---

## Table 7. Drop Points Data

| Field Name | Data Type | Size | Description |
|------------|-----------|------|-------------|
| id | BIGINT UNSIGNED | 20 | Unique identifier of drop point |
| trip_id | BIGINT UNSIGNED | 20 | Foreign key reference to trips table |
| passenger_id | BIGINT UNSIGNED | 20 | Foreign key reference to users table (passenger) |
| address | VARCHAR | 255 | Address of drop point location |
| latitude | DECIMAL | 10,8 | Latitude coordinate of drop point |
| longitude | DECIMAL | 10,8 | Longitude coordinate of drop point |
| sequence_order | INT | 11 | Order of drop point in sequence |
| status | ENUM | - | Status of drop point (requested, forwarded, confirmed, completed, cancelled) |
| requested_at | TIMESTAMP | - | Timestamp when drop point was requested |
| forwarded_at | TIMESTAMP | - | Timestamp when request was forwarded to driver |
| confirmed_at | TIMESTAMP | - | Timestamp when driver confirmed drop point |
| completed_at | TIMESTAMP | - | Timestamp when drop point was completed |
| notes | TEXT | - | Additional notes about drop point |
| created_at | TIMESTAMP | - | Timestamp when record was created |
| updated_at | TIMESTAMP | - | Timestamp when record was last updated |

---

## Table 8. Bus Locations Data

| Field Name | Data Type | Size | Description |
|------------|-----------|------|-------------|
| id | BIGINT UNSIGNED | 20 | Unique identifier of location record |
| trip_id | BIGINT UNSIGNED | 20 | Foreign key reference to trips table |
| bus_id | BIGINT UNSIGNED | 20 | Foreign key reference to buses table |
| latitude | DECIMAL | 10,8 | Latitude coordinate of bus location |
| longitude | DECIMAL | 10,8 | Longitude coordinate of bus location |
| speed | DECIMAL | 6,2 | Speed of bus in km/h |
| heading | DECIMAL | 5,2 | Direction of bus in degrees (0-360) |
| recorded_at | TIMESTAMP | - | Timestamp when location was recorded |
| created_at | TIMESTAMP | - | Timestamp when record was created |
| updated_at | TIMESTAMP | - | Timestamp when record was last updated |

---

## Table 9. Bus Logs Data

| Field Name | Data Type | Size | Description |
|------------|-----------|------|-------------|
| id | BIGINT UNSIGNED | 20 | Unique identifier of log entry |
| bus_id | BIGINT UNSIGNED | 20 | Foreign key reference to buses table |
| trip_id | BIGINT UNSIGNED | 20 | Foreign key reference to trips table (nullable) |
| user_id | BIGINT UNSIGNED | 20 | Foreign key reference to users table (nullable) |
| action | VARCHAR | 255 | Type of action logged (departure, arrival, maintenance, etc.) |
| description | TEXT | - | Description of logged action |
| metadata | JSON | - | Additional data in JSON format |
| log_time | TIMESTAMP | - | Timestamp when action occurred |
| created_at | TIMESTAMP | - | Timestamp when record was created |
| updated_at | TIMESTAMP | - | Timestamp when record was last updated |

---

## Table 10. Notifications Data

| Field Name | Data Type | Size | Description |
|------------|-----------|------|-------------|
| id | BIGINT UNSIGNED | 20 | Unique identifier of notification |
| user_id | BIGINT UNSIGNED | 20 | Foreign key reference to users table |
| type | VARCHAR | 255 | Type of notification |
| title | VARCHAR | 255 | Title of notification |
| message | TEXT | - | Message content of notification |
| data | JSON | - | Additional data in JSON format |
| is_read | BOOLEAN | 1 | Read status of notification (true = read, false = unread) |
| read_at | TIMESTAMP | - | Timestamp when notification was read |
| created_at | TIMESTAMP | - | Timestamp when record was created |
| updated_at | TIMESTAMP | - | Timestamp when record was last updated |

---

## Table 11. Driver Issues Data

| Field Name | Data Type | Size | Description |
|------------|-----------|------|-------------|
| id | BIGINT UNSIGNED | 20 | Unique identifier of driver issue |
| driver_id | BIGINT UNSIGNED | 20 | Foreign key reference to users table (driver) |
| bus_id | BIGINT UNSIGNED | 20 | Foreign key reference to buses table (nullable) |
| type | VARCHAR | 255 | Type of issue (mechanical, accident, other) |
| description | TEXT | - | Description of issue |
| status | VARCHAR | 255 | Status of issue (open, resolved) |
| resolved_at | TIMESTAMP | - | Timestamp when issue was resolved |
| created_at | TIMESTAMP | - | Timestamp when record was created |
| updated_at | TIMESTAMP | - | Timestamp when record was last updated |

---

## Table 12. Password Reset Tokens Data

| Field Name | Data Type | Size | Description |
|------------|-----------|------|-------------|
| email | VARCHAR | 255 | Email address of user requesting password reset (primary key) |
| token | VARCHAR | 255 | Password reset token |
| created_at | TIMESTAMP | - | Timestamp when token was created |

---

## Table 13. Sessions Data

| Field Name | Data Type | Size | Description |
|------------|-----------|------|-------------|
| id | VARCHAR | 255 | Session identifier (primary key) |
| user_id | BIGINT UNSIGNED | 20 | Foreign key reference to users table (nullable) |
| ip_address | VARCHAR | 45 | IP address of session |
| user_agent | TEXT | - | User agent string of session |
| payload | LONGTEXT | - | Session data payload |
| last_activity | INT | 11 | Timestamp of last activity in session |

---

## Data Type Reference

### Common Data Types Used

| Laravel Type | SQL Equivalent | Typical Size | Description |
|--------------|----------------|--------------|-------------|
| id() | BIGINT UNSIGNED | 20 | Auto-incrementing primary key |
| string() | VARCHAR | 255 (default) | Variable-length string |
| text() | TEXT | - | Large text field |
| integer() | INT | 11 | Integer number |
| decimal(x,y) | DECIMAL(x,y) | x,y | Decimal number with precision |
| boolean() | BOOLEAN/TINYINT | 1 | Boolean value (0 or 1) |
| enum() | ENUM | - | Enumeration of values |
| timestamp() | TIMESTAMP | - | Date and time |
| date() | DATE | - | Date only |
| time() | TIME | - | Time only |
| json() | JSON | - | JSON data |
| foreignId() | BIGINT UNSIGNED | 20 | Foreign key reference |

---

## Relationship Summary

### Primary Relationships

1. **Users** → **Bus Assignments** (driver_id, conductor_id)
2. **Buses** → **Bus Assignments** (bus_id)
3. **Buses** → **Schedules** (bus_id)
4. **Routes** → **Schedules** (route_id)
5. **Schedules** → **Trips** (schedule_id)
6. **Bus Assignments** → **Trips** (bus_assignment_id)
7. **Trips** → **Drop Points** (trip_id)
8. **Users** → **Drop Points** (passenger_id)
9. **Trips** → **Bus Locations** (trip_id)
10. **Buses** → **Bus Locations** (bus_id)
11. **Buses** → **Bus Logs** (bus_id)
12. **Trips** → **Bus Logs** (trip_id)
13. **Users** → **Bus Logs** (user_id)
14. **Users** → **Notifications** (user_id)
15. **Users** → **Driver Issues** (driver_id)
16. **Buses** → **Driver Issues** (bus_id)

---

## Notes

1. **Foreign Keys**: All foreign key relationships use `BIGINT UNSIGNED` with size 20, matching Laravel's default `id()` type.

2. **Timestamps**: The `created_at` and `updated_at` fields are automatically managed by Laravel's Eloquent ORM.

3. **Decimal Precision**: 
   - Coordinates use `DECIMAL(10,8)` for high precision (approximately 1.1mm accuracy)
   - Distance uses `DECIMAL(8,2)` for kilometers
   - Speed uses `DECIMAL(6,2)` for km/h
   - Heading uses `DECIMAL(5,2)` for degrees

4. **Enum Values**:
   - **User Role**: admin, supervisor, driver, conductor, passenger
   - **Bus Status**: active, maintenance, inactive
   - **Assignment Status**: active, completed, cancelled
   - **Schedule Status**: scheduled, departed, in_progress, completed, cancelled
   - **Trip Status**: scheduled, in_progress, completed, cancelled
   - **Drop Point Status**: requested, forwarded, confirmed, completed, cancelled
   - **Day of Week**: monday, tuesday, wednesday, thursday, friday, saturday, sunday

5. **Unique Constraints**:
   - Users.email (unique)
   - Buses.bus_number (unique)
   - Buses.license_plate (unique)
   - Bus Assignments: bus_id + assignment_date (unique combination)

6. **Nullable Fields**: Fields marked as nullable can contain NULL values and are optional.

7. **Cascade Deletes**: Most foreign key relationships are set to cascade on delete, meaning related records are automatically deleted when the parent record is deleted.

