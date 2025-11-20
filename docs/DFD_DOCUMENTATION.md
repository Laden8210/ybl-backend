# Data Flow Diagram (DFD) Documentation
## Bus Dropping Point System

---

## Table of Contents
1. [Level 0 DFD (Context Diagram)](#level-0-dfd)
2. [Level 1 DFD](#level-1-dfd)
3. [Process Descriptions](#process-descriptions)
4. [Data Store Definitions](#data-store-definitions)
5. [External Entity Definitions](#external-entity-definitions)
6. [Data Flow Definitions](#data-flow-definitions)

---

## Level 0 DFD

### Overview
The Level 0 DFD represents the highest level view of the Bus Dropping Point System, showing the main processes and their interactions with external entities and data stores.

### Diagram Structure

```
┌─────────────────────────────────────────────────────────────────────────┐
│                        BUS DROPPING POINT SYSTEM                        │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
        ┌───────────────────────────┼───────────────────────────┐
        │                           │                           │
        ▼                           ▼                           ▼
┌──────────────┐          ┌──────────────┐          ┌──────────────┐
│  1.0 User    │          │  2.0 Trip    │          │  3.0 Drop    │
│ Management   │          │  Management  │          │  Point       │
│              │          │              │          │  Management  │
└──────────────┘          └──────────────┘          └──────────────┘
        │                           │                           │
        │                           │                           │
        ▼                           ▼                           ▼
┌──────────────┐          ┌──────────────┐          ┌──────────────┐
│  4.0 GPS     │          │  5.0         │          │  6.0         │
│  Tracking    │          │  Notification│          │  Reporting   │
│              │          │              │          │              │
└──────────────┘          └──────────────┘          └──────────────┘
```

### External Entities

| Entity | Description |
|--------|-------------|
| **Admin** | System administrator managing buses, staff, and assignments |
| **Supervisor** | Monitors operations and verifies transportation details |
| **Driver** | Operates the bus and confirms drop-off points |
| **Conductor** | Manages drop point requests and forwards to driver |
| **Passenger** | Requests drop points and tracks bus location |
| **GPS Service** | Provides real-time location data |
| **Google Maps API** | Provides map visualization and routing services |

### Data Stores

| Data Store | Description |
|------------|-------------|
| **D1: Users** | Stores user accounts (Admin, Supervisor, Driver, Conductor, Passenger) |
| **D2: Buses** | Stores bus fleet information |
| **D3: Routes** | Stores route definitions and waypoints |
| **D4: Schedules** | Stores bus schedules and departure times |
| **D5: Trips** | Stores active and completed trip records |
| **D6: Drop Points** | Stores drop point requests and their status |
| **D7: Bus Locations** | Stores real-time GPS location data |
| **D8: Bus Logs** | Stores activity logs and audit trails |

### Level 0 Data Flows

#### Process 1.0: User Management

| From | To | Data Flow | Description |
|------|-----|-----------|-------------|
| Admin | 1.0 | User Registration Data | Admin creates staff accounts |
| Passenger | 1.0 | Registration Request | Passenger self-registers |
| 1.0 | D1 | User Data | Store/update user information |
| D1 | 1.0 | User Data | Retrieve user information |
| 1.0 | Admin | User Account Info | Return created account details |
| 1.0 | Passenger | Registration Confirmation | Confirm successful registration |
| 1.0 | Admin | Staff List | Return list of staff members |
| 1.0 | Supervisor | Profile Data | Return supervisor profile |
| 1.0 | Driver | Profile Data | Return driver profile |
| 1.0 | Conductor | Profile Data | Return conductor profile |
| 1.0 | Passenger | Profile Data | Return passenger profile |

#### Process 2.0: Trip Management

| From | To | Data Flow | Description |
|------|-----|-----------|-------------|
| Admin | 2.0 | Bus Assignment | Assign staff to buses |
| Driver | 2.0 | Trip Start Data | Driver starts a trip |
| Conductor | 2.0 | Trip Details | Conductor inputs passenger count |
| Supervisor | 2.0 | Trip Verification | Supervisor confirms trip details |
| 2.0 | D2 | Bus Data | Store/retrieve bus information |
| 2.0 | D3 | Route Data | Retrieve route information |
| 2.0 | D4 | Schedule Data | Retrieve schedule information |
| 2.0 | D5 | Trip Data | Store/update trip records |
| D5 | 2.0 | Trip Data | Retrieve trip information |
| 2.0 | Driver | Trip Information | Return trip details to driver |
| 2.0 | Conductor | Trip Information | Return trip details to conductor |
| 2.0 | Supervisor | Trip Details | Return trip information for monitoring |
| 2.0 | Passenger | Schedule Information | Return bus schedules |
| 2.0 | Admin | Bus Log Data | Return activity logs |

#### Process 3.0: Drop Point Management

| From | To | Data Flow | Description |
|------|-----|-----------|-------------|
| Passenger | 3.0 | Drop Point Request | Passenger requests drop-off location |
| Conductor | 3.0 | Forward Request | Conductor forwards request to driver |
| Driver | 3.0 | Drop Point Confirmation | Driver confirms drop point |
| Driver | 3.0 | Drop Point Completion | Driver marks drop point as completed |
| 3.0 | D5 | Trip Data | Retrieve trip information |
| 3.0 | D6 | Drop Point Data | Store/update drop point records |
| D6 | 3.0 | Drop Point Data | Retrieve drop point information |
| 3.0 | Passenger | Drop Point Status | Return request status |
| 3.0 | Conductor | Drop Point List | Return list of requests |
| 3.0 | Driver | Drop Point List | Return list of confirmed drop points |

#### Process 4.0: GPS Tracking

| From | To | Data Flow | Description |
|------|-----|-----------|-------------|
| GPS Service | 4.0 | GPS Coordinates | Real-time location data |
| Driver | 4.0 | Location Update Request | Driver sends location update |
| 4.0 | D7 | Location Data | Store GPS coordinates |
| D7 | 4.0 | Location Data | Retrieve location history |
| 4.0 | D5 | Trip Data | Link location to trip |
| 4.0 | Passenger | Bus Location | Return real-time bus location |
| 4.0 | Supervisor | Bus Location | Return bus location for monitoring |
| 4.0 | Admin | Bus Location | Return bus location for tracking |
| 4.0 | Google Maps API | Location Data | Send coordinates for map display |
| Google Maps API | 4.0 | Map Data | Return map visualization |

#### Process 5.0: Notification

| From | To | Data Flow | Description |
|------|-----|-----------|-------------|
| 3.0 | 5.0 | Drop Point Status Change | Drop point status updated |
| 2.0 | 5.0 | Trip Status Change | Trip status updated |
| 4.0 | 5.0 | Location Update | New location data available |
| 5.0 | Conductor | Notification | Notify conductor of new requests |
| 5.0 | Driver | Notification | Notify driver of forwarded requests |
| 5.0 | Passenger | Notification | Notify passenger of status updates |

#### Process 6.0: Reporting

| From | To | Data Flow | Description |
|------|-----|-----------|-------------|
| Admin | 6.0 | Report Request | Admin requests reports |
| Supervisor | 6.0 | Report Request | Supervisor requests reports |
| 6.0 | D5 | Trip Data | Retrieve trip records |
| 6.0 | D6 | Drop Point Data | Retrieve drop point records |
| 6.0 | D7 | Location Data | Retrieve location data |
| 6.0 | D8 | Log Data | Retrieve activity logs |
| 6.0 | Admin | Report Data | Return generated reports |
| 6.0 | Supervisor | Report Data | Return generated reports |

---

## Level 1 DFD

### Overview
Level 1 DFD breaks down the main processes from Level 0 into more detailed sub-processes, showing the internal data flows and interactions.

---

### Process 1.0: User Management (Level 1)

```
┌─────────────────────────────────────────────────────────────────┐
│                     1.0 USER MANAGEMENT                          │
└─────────────────────────────────────────────────────────────────┘
        │
        ├─────────────────┬─────────────────┬─────────────────┐
        ▼                 ▼                 ▼                 ▼
┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│  1.1         │  │  1.2          │  │  1.3         │  │  1.4         │
│  Authenticate│  │  Register     │  │  Manage      │  │  Assign      │
│  User        │  │  User         │  │  Profile     │  │  Staff       │
└──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘
        │                 │                 │                 │
        │                 │                 │                 │
        ▼                 ▼                 ▼                 ▼
      D1: Users        D1: Users        D1: Users        D2: Buses
```

#### Sub-Processes

**1.1 Authenticate User**
- Validates user credentials
- Generates authentication token
- Checks account status

**1.2 Register User**
- Creates new passenger account
- Validates registration data
- Stores user information

**1.3 Manage Profile**
- Updates user profile information
- Changes password
- Retrieves profile data

**1.4 Assign Staff**
- Assigns drivers to buses
- Assigns conductors to buses
- Links staff to bus assignments

#### Data Flows (Process 1.0 Level 1)

| From | To | Data Flow | Description |
|------|-----|-----------|-------------|
| Admin/Passenger | 1.1 | Login Credentials | Email and password |
| 1.1 | D1 | User Query | Check user credentials |
| D1 | 1.1 | User Data | Return user information |
| 1.1 | Admin/Passenger | Authentication Token | Return access token |
| Passenger | 1.2 | Registration Data | Name, email, password, phone |
| 1.2 | D1 | New User Data | Store new user |
| D1 | 1.2 | User ID | Return created user ID |
| 1.2 | Passenger | Registration Confirmation | Confirm successful registration |
| User | 1.3 | Profile Update Request | Updated profile information |
| 1.3 | D1 | Updated Profile Data | Store updated information |
| D1 | 1.3 | Profile Data | Retrieve current profile |
| 1.3 | User | Updated Profile | Return updated profile |
| Admin | 1.4 | Assignment Data | Staff and bus assignment |
| 1.4 | D2 | Bus Assignment | Store assignment |
| 1.4 | D1 | Staff Assignment | Link staff to bus |
| D2 | 1.4 | Bus Data | Retrieve bus information |
| 1.4 | Admin | Assignment Confirmation | Confirm assignment |

---

### Process 2.0: Trip Management (Level 1)

```
┌─────────────────────────────────────────────────────────────────┐
│                     2.0 TRIP MANAGEMENT                          │
└─────────────────────────────────────────────────────────────────┘
        │
        ├─────────────────┬─────────────────┬─────────────────┐
        ▼                 ▼                 ▼                 ▼
┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│  2.1         │  │  2.2         │  │  2.3         │  │  2.4         │
│  Create      │  │  Update      │  │  Monitor     │  │  Generate    │
│  Trip        │  │  Trip Status │  │  Trip        │  │  Logs        │
└──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘
        │                 │                 │                 │
        │                 │                 │                 │
        ▼                 ▼                 ▼                 ▼
      D5: Trips        D5: Trips        D5: Trips        D8: Bus Logs
```

#### Sub-Processes

**2.1 Create Trip**
- Initializes new trip from schedule
- Links bus and route to trip
- Sets initial trip status

**2.2 Update Trip Status**
- Updates trip status (loading, in_progress, completed)
- Records trip start/end times
- Updates passenger count

**2.3 Monitor Trip**
- Retrieves trip details
- Tracks trip progress
- Provides real-time trip information

**2.4 Generate Logs**
- Records trip activities
- Creates audit trail
- Stores activity logs

#### Data Flows (Process 2.0 Level 1)

| From | To | Data Flow | Description |
|------|-----|-----------|-------------|
| Driver | 2.1 | Trip Start Request | Driver initiates trip |
| 2.1 | D4 | Schedule Query | Retrieve schedule information |
| D4 | 2.1 | Schedule Data | Return schedule details |
| 2.1 | D2 | Bus Query | Retrieve bus information |
| D2 | 2.1 | Bus Data | Return bus details |
| 2.1 | D3 | Route Query | Retrieve route information |
| D3 | 2.1 | Route Data | Return route details |
| 2.1 | D5 | New Trip Data | Create trip record |
| D5 | 2.1 | Trip ID | Return created trip ID |
| 2.1 | Driver | Trip Information | Return trip details |
| Conductor | 2.2 | Passenger Count | Update passenger count |
| Driver | 2.2 | Status Update | Update trip status |
| 2.2 | D5 | Updated Trip Data | Store status update |
| D5 | 2.2 | Trip Data | Retrieve current trip |
| 2.2 | Driver/Conductor | Updated Status | Return updated status |
| Supervisor | 2.3 | Trip Query | Request trip information |
| 2.3 | D5 | Trip Query | Retrieve trip data |
| D5 | 2.3 | Trip Data | Return trip information |
| 2.3 | Supervisor | Trip Details | Return trip information |
| 2.4 | D5 | Trip Data | Retrieve trip records |
| 2.4 | D8 | Log Data | Store activity logs |
| Admin | 2.4 | Log Request | Request activity logs |
| D8 | 2.4 | Log Data | Retrieve stored logs |
| 2.4 | Admin | Log Data | Return activity logs |

---

### Process 3.0: Drop Point Management (Level 1)

```
┌─────────────────────────────────────────────────────────────────┐
│                  3.0 DROP POINT MANAGEMENT                      │
└─────────────────────────────────────────────────────────────────┘
        │
        ├─────────────────┬─────────────────┬─────────────────┐
        ▼                 ▼                 ▼                 ▼
┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│  3.1         │  │  3.2         │  │  3.3         │  │  3.4         │
│  Request     │  │  Forward     │  │  Confirm     │  │  Complete    │
│  Drop Point  │  │  Request     │  │  Drop Point  │  │  Drop Point  │
└──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘
        │                 │                 │                 │
        │                 │                 │                 │
        ▼                 ▼                 ▼                 ▼
      D6: Drop Points  D6: Drop Points  D6: Drop Points  D6: Drop Points
```

#### Sub-Processes

**3.1 Request Drop Point**
- Receives drop point request from passenger
- Validates trip status
- Assigns sequence order
- Creates drop point record

**3.2 Forward Request**
- Conductor forwards request to driver
- Updates drop point status
- Notifies driver

**3.3 Confirm Drop Point**
- Driver confirms drop point
- Updates status to confirmed
- Records confirmation time

**3.4 Complete Drop Point**
- Driver marks drop point as completed
- Updates completion time
- Finalizes drop point record

#### Data Flows (Process 3.0 Level 1)

| From | To | Data Flow | Description |
|------|-----|-----------|-------------|
| Passenger | 3.1 | Drop Point Request | Address, coordinates, trip ID |
| 3.1 | D5 | Trip Query | Check trip status |
| D5 | 3.1 | Trip Data | Return trip information |
| 3.1 | D6 | New Drop Point Data | Create drop point record |
| D6 | 3.1 | Drop Point ID | Return created drop point ID |
| 3.1 | Passenger | Request Confirmation | Confirm request created |
| Conductor | 3.2 | Forward Request | Drop point ID to forward |
| 3.2 | D6 | Drop Point Query | Retrieve drop point |
| D6 | 3.2 | Drop Point Data | Return drop point information |
| 3.2 | D6 | Updated Status | Update status to forwarded |
| 3.2 | Driver | Forwarded Request | Notify driver |
| Driver | 3.3 | Confirmation Request | Drop point ID to confirm |
| 3.3 | D6 | Drop Point Query | Retrieve drop point |
| D6 | 3.3 | Drop Point Data | Return drop point information |
| 3.3 | D6 | Confirmed Status | Update status to confirmed |
| 3.3 | Passenger | Confirmation Notification | Notify passenger |
| Driver | 3.4 | Completion Request | Drop point ID to complete |
| 3.4 | D6 | Drop Point Query | Retrieve drop point |
| D6 | 3.4 | Drop Point Data | Return drop point information |
| 3.4 | D6 | Completed Status | Update status to completed |
| 3.4 | Passenger | Completion Notification | Notify passenger |

---

### Process 4.0: GPS Tracking (Level 1)

```
┌─────────────────────────────────────────────────────────────────┐
│                     4.0 GPS TRACKING                             │
└─────────────────────────────────────────────────────────────────┘
        │
        ├─────────────────┬─────────────────┬─────────────────┐
        ▼                 ▼                 ▼                 ▼
┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
│  4.1         │  │  4.2         │  │  4.3         │  │  4.4         │
│  Receive     │  │  Store       │  │  Retrieve    │  │  Display     │
│  Location    │  │  Location    │  │  Location    │  │  on Map      │
└──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘
        │                 │                 │                 │
        │                 │                 │                 │
        ▼                 ▼                 ▼                 ▼
   GPS Service        D7: Locations    D7: Locations    Google Maps API
```

#### Sub-Processes

**4.1 Receive Location**
- Receives GPS coordinates from GPS service
- Validates coordinate data
- Processes location updates

**4.2 Store Location**
- Stores location data in database
- Links location to trip
- Records timestamp

**4.3 Retrieve Location**
- Retrieves current bus location
- Retrieves location history
- Provides location data to users

**4.4 Display on Map**
- Sends coordinates to Google Maps API
- Receives map visualization
- Displays bus location on map

#### Data Flows (Process 4.0 Level 1)

| From | To | Data Flow | Description |
|------|-----|-----------|-------------|
| GPS Service | 4.1 | GPS Coordinates | Latitude, longitude, speed, heading |
| Driver | 4.1 | Location Update | Manual location update |
| 4.1 | 4.2 | Validated Coordinates | Processed location data |
| 4.2 | D7 | Location Data | Store GPS coordinates |
| 4.2 | D5 | Trip Link | Link location to trip |
| D7 | 4.2 | Location ID | Return stored location ID |
| Passenger | 4.3 | Location Request | Request bus location |
| Supervisor | 4.3 | Location Request | Request bus location |
| Admin | 4.3 | Location Request | Request bus location |
| 4.3 | D7 | Location Query | Retrieve location data |
| D7 | 4.3 | Location Data | Return location information |
| 4.3 | User | Bus Location | Return current location |
| 4.4 | Google Maps API | Coordinates | Send location for mapping |
| Google Maps API | 4.4 | Map Data | Return map visualization |
| 4.4 | User | Map Display | Display bus on map |

---

## Process Descriptions

### Level 0 Processes

#### 1.0 User Management
Manages all user-related operations including authentication, registration, profile management, and staff assignments. Handles user accounts for all roles (Admin, Supervisor, Driver, Conductor, Passenger).

**Inputs:**
- Registration data
- Login credentials
- Profile update requests
- Staff assignment data

**Outputs:**
- Authentication tokens
- User profile information
- Registration confirmations
- Assignment confirmations

**Data Stores:**
- D1: Users
- D2: Buses

#### 2.0 Trip Management
Manages the complete lifecycle of bus trips from creation to completion. Handles trip initialization, status updates, monitoring, and logging.

**Inputs:**
- Trip start requests
- Trip status updates
- Passenger count updates
- Trip queries

**Outputs:**
- Trip information
- Schedule data
- Bus logs
- Trip status updates

**Data Stores:**
- D2: Buses
- D3: Routes
- D4: Schedules
- D5: Trips
- D8: Bus Logs

#### 3.0 Drop Point Management
Manages the complete workflow of drop point requests from passenger request through completion. Handles request creation, forwarding, confirmation, and completion.

**Inputs:**
- Drop point requests
- Forward requests
- Confirmation requests
- Completion requests

**Outputs:**
- Drop point status
- Drop point lists
- Notifications

**Data Stores:**
- D5: Trips
- D6: Drop Points

#### 4.0 GPS Tracking
Manages real-time location tracking of buses. Receives GPS data, stores it, and provides location information to users with map visualization.

**Inputs:**
- GPS coordinates
- Location update requests
- Location queries

**Outputs:**
- Bus location data
- Map visualizations
- Location history

**Data Stores:**
- D5: Trips
- D7: Bus Locations

**External Interfaces:**
- GPS Service
- Google Maps API

#### 5.0 Notification
Manages system notifications for various events including drop point status changes, trip updates, and location changes.

**Inputs:**
- Status change events
- Location updates
- Trip updates

**Outputs:**
- Notifications to users

#### 6.0 Reporting
Generates reports and analytics based on trip data, drop point data, location data, and activity logs.

**Inputs:**
- Report requests
- Historical data queries

**Outputs:**
- Generated reports
- Analytics data

**Data Stores:**
- D5: Trips
- D6: Drop Points
- D7: Bus Locations
- D8: Bus Logs

---

## Data Store Definitions

### D1: Users
Stores all user account information including authentication credentials and profile data.

**Attributes:**
- User ID (Primary Key)
- Name
- Email
- Password (hashed)
- Role (admin, supervisor, driver, conductor, passenger)
- Phone
- Address
- Employee ID (for staff)
- License Number (for drivers)
- Status (active, inactive)
- Created/Updated timestamps

**Access:**
- Read/Write by Process 1.0

### D2: Buses
Stores bus fleet information and assignments.

**Attributes:**
- Bus ID (Primary Key)
- Bus Number
- License Plate
- Model
- Capacity
- Status (active, inactive, maintenance)
- Created/Updated timestamps

**Access:**
- Read/Write by Process 1.0, 2.0

### D3: Routes
Stores route definitions including waypoints and route details.

**Attributes:**
- Route ID (Primary Key)
- Route Name
- Description
- Start Point
- End Point
- Distance
- Estimated Duration
- Waypoints
- Status (active, inactive)
- Created/Updated timestamps

**Access:**
- Read by Process 2.0

### D4: Schedules
Stores bus schedules and departure times.

**Attributes:**
- Schedule ID (Primary Key)
- Bus ID (Foreign Key)
- Route ID (Foreign Key)
- Departure Time
- Arrival Time
- Day of Week
- Is Recurring
- Effective Date
- End Date
- Status
- Created/Updated timestamps

**Access:**
- Read by Process 2.0

### D5: Trips
Stores active and completed trip records.

**Attributes:**
- Trip ID (Primary Key)
- Bus ID (Foreign Key)
- Route ID (Foreign Key)
- Driver ID (Foreign Key)
- Conductor ID (Foreign Key)
- Trip Date
- Status (scheduled, loading, in_progress, completed, cancelled)
- Passenger Count
- Start Time
- End Time
- Created/Updated timestamps

**Access:**
- Read/Write by Process 2.0, 3.0, 4.0

### D6: Drop Points
Stores drop point requests and their status throughout the workflow.

**Attributes:**
- Drop Point ID (Primary Key)
- Trip ID (Foreign Key)
- Passenger ID (Foreign Key)
- Address
- Latitude
- Longitude
- Sequence Order
- Status (requested, forwarded, confirmed, completed, cancelled)
- Requested At
- Forwarded At
- Confirmed At
- Completed At
- Notes
- Created/Updated timestamps

**Access:**
- Read/Write by Process 3.0

### D7: Bus Locations
Stores real-time GPS location data for buses.

**Attributes:**
- Location ID (Primary Key)
- Trip ID (Foreign Key)
- Bus ID (Foreign Key)
- Latitude
- Longitude
- Speed
- Heading
- Recorded At
- Created/Updated timestamps

**Access:**
- Read/Write by Process 4.0

### D8: Bus Logs
Stores activity logs and audit trails for system operations.

**Attributes:**
- Log ID (Primary Key)
- User ID (Foreign Key)
- Action Type
- Entity Type
- Entity ID
- Description
- IP Address
- Timestamp
- Created timestamp

**Access:**
- Write by Process 2.0
- Read by Process 6.0

---

## External Entity Definitions

### Admin
System administrator who manages the overall system operations.

**Responsibilities:**
- Create and manage staff accounts
- Assign staff to buses
- View bus logs and reports
- Track bus locations
- Manage bus fleet

**Interactions:**
- User Management (1.0)
- Trip Management (2.0)
- GPS Tracking (4.0)
- Reporting (6.0)

### Supervisor
Monitors and verifies transportation operations.

**Responsibilities:**
- Monitor bus operations
- Track bus locations
- Verify trip details
- Confirm transportation information
- View reports

**Interactions:**
- Trip Management (2.0)
- GPS Tracking (4.0)
- Reporting (6.0)

### Driver
Operates the bus and manages drop-off confirmations.

**Responsibilities:**
- Start and manage trips
- Update bus location
- Confirm drop points
- Complete drop points

**Interactions:**
- Trip Management (2.0)
- Drop Point Management (3.0)
- GPS Tracking (4.0)

### Conductor
Acts as communication bridge between passengers and driver.

**Responsibilities:**
- Add drop point requests
- Forward requests to driver
- Update passenger count
- View drop point locations

**Interactions:**
- Trip Management (2.0)
- Drop Point Management (3.0)

### Passenger
Uses the system to request drop points and track bus location.

**Responsibilities:**
- Register account
- Request drop points
- Track bus location
- View schedules

**Interactions:**
- User Management (1.0)
- Drop Point Management (3.0)
- GPS Tracking (4.0)

### GPS Service
External service providing real-time GPS location data.

**Responsibilities:**
- Provide GPS coordinates
- Track device location
- Update location data

**Interactions:**
- GPS Tracking (4.0)

### Google Maps API
External service providing map visualization and routing.

**Responsibilities:**
- Display maps
- Show routes
- Visualize locations
- Provide routing information

**Interactions:**
- GPS Tracking (4.0)

---

## Data Flow Definitions

### Authentication Flows

**Login Credentials**
- **Source:** Admin, Supervisor, Driver, Conductor, Passenger
- **Destination:** Process 1.0 (User Management)
- **Content:** Email, password, device name
- **Purpose:** Authenticate user and generate access token

**Authentication Token**
- **Source:** Process 1.0 (User Management)
- **Destination:** Admin, Supervisor, Driver, Conductor, Passenger
- **Content:** Bearer token for API authentication
- **Purpose:** Authorize subsequent API requests

### Registration Flows

**Registration Request**
- **Source:** Passenger
- **Destination:** Process 1.0 (User Management)
- **Content:** Name, email, password, phone, address
- **Purpose:** Create new passenger account

**Registration Confirmation**
- **Source:** Process 1.0 (User Management)
- **Destination:** Passenger
- **Content:** User data, authentication token
- **Purpose:** Confirm successful registration

### Trip Flows

**Trip Start Data**
- **Source:** Driver
- **Destination:** Process 2.0 (Trip Management)
- **Content:** Bus ID, route information, start time
- **Purpose:** Initialize new trip

**Trip Information**
- **Source:** Process 2.0 (Trip Management)
- **Destination:** Driver, Conductor, Supervisor, Passenger
- **Content:** Trip details, status, schedule information
- **Purpose:** Provide trip information to users

### Drop Point Flows

**Drop Point Request**
- **Source:** Passenger
- **Destination:** Process 3.0 (Drop Point Management)
- **Content:** Trip ID, address, latitude, longitude
- **Purpose:** Request drop-off at specific location

**Drop Point Status**
- **Source:** Process 3.0 (Drop Point Management)
- **Destination:** Passenger, Conductor, Driver
- **Content:** Drop point ID, status, timestamps
- **Purpose:** Communicate drop point status changes

### Location Flows

**GPS Coordinates**
- **Source:** GPS Service
- **Destination:** Process 4.0 (GPS Tracking)
- **Content:** Latitude, longitude, speed, heading, timestamp
- **Purpose:** Provide real-time location data

**Bus Location**
- **Source:** Process 4.0 (GPS Tracking)
- **Destination:** Passenger, Supervisor, Admin
- **Content:** Bus number, route, coordinates, speed, heading, last updated
- **Purpose:** Display current bus location

**Map Data**
- **Source:** Google Maps API
- **Destination:** Process 4.0 (GPS Tracking)
- **Content:** Map visualization, route display
- **Purpose:** Visualize bus location on map

### Notification Flows

**Notification**
- **Source:** Process 5.0 (Notification)
- **Destination:** Passenger, Conductor, Driver
- **Content:** Notification type, message, related entity
- **Purpose:** Alert users of status changes

### Reporting Flows

**Report Request**
- **Source:** Admin, Supervisor
- **Destination:** Process 6.0 (Reporting)
- **Content:** Report type, date range, filters
- **Purpose:** Request specific report

**Report Data**
- **Source:** Process 6.0 (Reporting)
- **Destination:** Admin, Supervisor
- **Content:** Aggregated data, statistics, charts
- **Purpose:** Provide insights and analytics

---

## Relationship Summary

### Process Relationships

1. **User Management (1.0) ↔ Trip Management (2.0)**
   - User Management provides staff assignments
   - Trip Management uses user data for trip creation

2. **Trip Management (2.0) ↔ Drop Point Management (3.0)**
   - Drop Point Management requires active trip
   - Trip Management provides trip status

3. **Trip Management (2.0) ↔ GPS Tracking (4.0)**
   - GPS Tracking links locations to trips
   - Trip Management provides trip context

4. **Drop Point Management (3.0) ↔ GPS Tracking (4.0)**
   - GPS Tracking helps locate drop points
   - Drop Point Management uses location data

5. **All Processes ↔ Notification (5.0)**
   - All processes trigger notifications
   - Notification system alerts users

6. **All Processes ↔ Reporting (6.0)**
   - All processes contribute data for reports
   - Reporting aggregates data from all processes

### Data Store Relationships

- **D1 (Users)** is accessed by Process 1.0
- **D2 (Buses)** is accessed by Processes 1.0, 2.0
- **D3 (Routes)** is accessed by Process 2.0
- **D4 (Schedules)** is accessed by Process 2.0
- **D5 (Trips)** is accessed by Processes 2.0, 3.0, 4.0
- **D6 (Drop Points)** is accessed by Process 3.0
- **D7 (Bus Locations)** is accessed by Process 4.0
- **D8 (Bus Logs)** is accessed by Processes 2.0, 6.0

---

## Notes

1. **Authentication:** All processes (except public registration) require authentication tokens from Process 1.0
2. **Status Flow:** Drop points follow a strict status flow: requested → forwarded → confirmed → completed
3. **Real-time Updates:** GPS Tracking (4.0) provides real-time location updates to multiple users simultaneously
4. **Data Integrity:** All processes maintain referential integrity through foreign key relationships
5. **Audit Trail:** Process 2.0 generates logs for all trip activities stored in D8
6. **Notifications:** Process 5.0 handles asynchronous notifications for better user experience
7. **Scalability:** The system is designed to handle multiple concurrent trips and location updates

