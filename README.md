### **Summary of System Functions**

The primary function of the Bus Dropping Point System is to **enhance the efficiency and accuracy of passenger drop-offs** in public bus transport. It uses GPS, real-time tracking, and a structured communication platform to solve issues like missed stops, communication barriers, and unfamiliarity with routes.

---

### **Detailed Functions by User Role**

#### **A. Admin Functions**
The Admin manages the system's core entities: staff, buses, and their assignments.
1.  **Login:** Authenticate with valid credentials to access the admin dashboard.
2.  **View Profile:** View personal admin account details.
3.  **Manage Bus Fleet:**
    *   **View Bus:** See a list of all buses in the system.
    *   **Add Bus:** Register a new bus into the system.
4.  **Manage Staff Accounts:**
    *   **Create Account:** Create new user accounts for Supervisors, Drivers, and Conductors.
5.  **Assign Staff to Buses:** Assign specific Drivers and Conductors to a specific bus.
6.  **View Bus Log:** Access and review logs of bus activities.
7.  **Track Bus Location:** Monitor the real-time location of buses on a map.

#### **B. Supervisor Functions**
The Supervisor verifies and monitors transportation operations.
1.  **Login:** Authenticate with valid credentials (account provided by Admin).
2.  **View Bus Departure Time:** Check the scheduled departure times of buses.
3.  **Track Bus Location:** Monitor the real-time location of buses on a map.
4.  **Check Transportation Details:** View bus information, assigned staff, and passenger counts.
5.  **Confirm Transportation Information:** Verify and validate the details checked in the system.

#### **C. Driver Functions**
The Driver is responsible for operating the bus and confirming drop-off points.
1.  **Login:** Authenticate with valid credentials (account provided by Admin).
2.  **View Profile:** View personal driver account details.
3.  **Input Transportation Details:** Enter or update trip-related information at the start of a journey.
4.  **View Drop Point Location:** See the intended drop-off points for passengers on a map, as communicated by the Conductor.
5.  **Confirm Drop-off Location:** Acknowledge and confirm arrival at a passenger's drop-off point.

#### **D. Conductor Functions**
The Conductor acts as the communication bridge between passengers and the driver for drop-off requests.
1.  **Login:** Authenticate with valid credentials (account provided by Admin).
2.  **View Profile:** View personal conductor account details.
3.  **Input Transportation Details:** Enter or update trip-related information, such as passenger count.
4.  **View Drop Point Location:** See the list of requested drop-off points.
5.  **Add Drop-off Location:** Input a passenger's desired drop-off point into the system.
6.  **Forward Request to Driver:** Send the added drop-off location to the Driver's interface for action.

#### **E. Passenger Functions**
The Passenger uses the system to plan their journey and track their bus.
1.  **Register User:** Create a new passenger account.
2.  **Login:** Authenticate with valid credentials to access passenger features.
3.  **View Profile:** View and manage personal passenger account details.
4.  **View Bus Departure Time:** Check the scheduled departure times of buses.
5.  **Track Bus Location:** View the real-time location of their bus on a map.

---

### **Core System Functions (Background/Technical)**
These are the underlying technologies that enable the user-facing functions listed above.
1.  **Real-Time GPS Tracking:** Continuously acquires and processes the bus's location using GPS.
2.  **Map Integration:** Uses the Google Maps API to display locations and routes.
3.  **Location-Based Assistance:** Provides visual and data-driven guidance to drivers and conductors for identifying precise drop-off points.
4.  **Structured Communication Relay:** Facilitates a digital chain of communication from Passenger -> Conductor -> Driver for drop-off requests.
5.  **Data Management:** Stores and manages all data (user accounts, bus details, trip logs, drop-off points) in a MySQL database.
