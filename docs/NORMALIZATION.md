# Database Normalization
## Bus Dropping Point System
### UNF to 3NF

This document demonstrates the normalization process from Unnormalized Form (UNF) to Third Normal Form (3NF) for the Bus Dropping Point System database.

---

## Table of Contents
1. [Trip Management Normalization](#trip-management-normalization)
2. [User and Bus Assignment Normalization](#user-and-bus-assignment-normalization)
3. [Drop Point Management Normalization](#drop-point-management-normalization)
4. [Schedule and Route Normalization](#schedule-and-route-normalization)

---

## Trip Management Normalization

### UNF (Unnormalized Form)

**Trip_Management_Table**

| Trip_ID | Bus_Number | Bus_Model | Bus_Capacity | Driver_Name | Driver_Email | Driver_Phone | Conductor_Name | Conductor_Email | Route_Name | Start_Point | End_Point | Distance | Departure_Time | Arrival_Time | Trip_Date | Passenger_Count | Status | Drop_Points |
|---------|------------|-----------|--------------|-------------|-------------|--------------|----------------|-----------------|------------|-------------|-----------|----------|-----------------|--------------|-----------|------------------|--------|------------|
| T001 | YBL-001 | Hyundai | 50 | John Doe | john@email.com | 09123456789 | Jane Smith | jane@email.com | Downtown Express | North Terminal | City Center | 15.5 | 08:00 | 09:00 | 2025-11-20 | 25 | In Progress | DP1:123 Main St, DP2:456 Oak Ave |
| T002 | YBL-002 | Toyota | 40 | Bob Wilson | bob@email.com | 09987654321 | Alice Brown | alice@email.com | Airport Shuttle | Airport | Downtown | 20.0 | 10:00 | 11:30 | 2025-11-20 | 30 | Completed | DP1:789 Pine Rd |

**Problems in UNF:**

The unnormalized form contains several critical problems that violate database design principles. The table contains repeating groups where Drop_Points can have multiple values stored in a single cell, which violates the atomicity requirement. The table also contains multiple attributes that should be in separate tables, such as bus information, driver information, and route information all mixed together. This creates significant data redundancy where bus information is repeated for each trip record. The structure leads to update anomalies where changing a bus model would require updating all trip records that reference that bus. Insert anomalies exist because it is impossible to add a new bus to the system without creating a trip record first. Delete anomalies are present because deleting a trip record might inadvertently delete important bus information that should be preserved independently.

---

### 1NF (First Normal Form)

**Rule:** Eliminate repeating groups and ensure each attribute contains atomic values.

**Trip_Table**

| Trip_ID | Bus_ID | Driver_ID | Conductor_ID | Route_ID | Departure_Time | Arrival_Time | Trip_Date | Passenger_Count | Status |
|---------|--------|-----------|--------------|----------|----------------|--------------|-----------|------------------|--------|
| T001 | B001 | D001 | C001 | R001 | 08:00 | 09:00 | 2025-11-20 | 25 | In Progress |
| T002 | B002 | D002 | C002 | R002 | 10:00 | 11:30 | 2025-11-20 | 30 | Completed |

**Bus_Table**

| Bus_ID | Bus_Number | Bus_Model | Bus_Capacity | Status |
|--------|------------|-----------|--------------|--------|
| B001 | YBL-001 | Hyundai | 50 | Active |
| B002 | YBL-002 | Toyota | 40 | Active |

**Driver_Table**

| Driver_ID | Driver_Name | Driver_Email | Driver_Phone | License_Number |
|-----------|-------------|--------------|--------------|-----------------|
| D001 | John Doe | john@email.com | 09123456789 | DL-12345 |
| D002 | Bob Wilson | bob@email.com | 09987654321 | DL-67890 |

**Conductor_Table**

| Conductor_ID | Conductor_Name | Conductor_Email | Conductor_Phone |
|--------------|----------------|-----------------|-----------------|
| C001 | Jane Smith | jane@email.com | 09111111111 |
| C002 | Alice Brown | alice@email.com | 09222222222 |

**Route_Table**

| Route_ID | Route_Name | Start_Point | End_Point | Distance |
|----------|------------|-------------|-----------|----------|
| R001 | Downtown Express | North Terminal | City Center | 15.5 |
| R002 | Airport Shuttle | Airport | Downtown | 20.0 |

**Drop_Point_Table**

| Drop_Point_ID | Trip_ID | Address | Sequence_Order | Status |
|---------------|---------|---------|----------------|--------|
| DP001 | T001 | 123 Main St | 1 | Requested |
| DP002 | T001 | 456 Oak Ave | 2 | Confirmed |
| DP003 | T002 | 789 Pine Rd | 1 | Completed |

**Explanation:**

The first normal form was achieved by removing all repeating groups and ensuring that each attribute contains atomic values. The Drop_Points field, which previously contained multiple values in a single cell, was separated into its own Drop_Point_Table where each drop point is stored as an individual record. All attributes were made atomic, meaning no composite values exist within any field. Separate tables were created for distinct entities including Bus, Driver, Conductor, and Route, which eliminates the redundancy of storing this information repeatedly in the trip table. Each table now has a primary key that uniquely identifies each record, and all attributes are single-valued, meaning each cell contains exactly one value.

**Problems Remaining:**

While first normal form eliminates repeating groups, some problems remain. Partial dependencies exist where attributes like Driver_Name depend on Driver_ID rather than Trip_ID, meaning these attributes are not fully dependent on the trip's primary key. Transitive dependencies are present where attributes like Bus_Model depend on Bus_ID rather than directly on Trip_ID, creating indirect relationships that violate third normal form requirements.

---

### 2NF (Second Normal Form)

**Rule:** Remove partial dependencies - all non-key attributes must be fully dependent on the primary key.

**Trip_Table** (No change - already in 2NF)

| Trip_ID | Bus_ID | Driver_ID | Conductor_ID | Route_ID | Departure_Time | Arrival_Time | Trip_Date | Passenger_Count | Status |
|---------|--------|-----------|--------------|----------|----------------|--------------|-----------|------------------|--------|
| T001 | B001 | D001 | C001 | R001 | 08:00 | 09:00 | 2025-11-20 | 25 | In Progress |
| T002 | B002 | D002 | C002 | R002 | 10:00 | 11:30 | 2025-11-20 | 30 | Completed |

**Bus_Table** (No change - already in 2NF)

| Bus_ID | Bus_Number | Bus_Model | Bus_Capacity | Status |
|--------|------------|-----------|--------------|--------|
| B001 | YBL-001 | Hyundai | 50 | Active |
| B002 | YBL-002 | Toyota | 40 | Active |

**User_Table** (Combined Driver and Conductor into single Users table)

| User_ID | Name | Email | Phone | Role | License_Number | Employee_ID |
|---------|------|-------|-------|------|----------------|-------------|
| D001 | John Doe | john@email.com | 09123456789 | driver | DL-12345 | EMP-001 |
| D002 | Bob Wilson | bob@email.com | 09987654321 | driver | DL-67890 | EMP-002 |
| C001 | Jane Smith | jane@email.com | 09111111111 | conductor | NULL | EMP-003 |
| C002 | Alice Brown | alice@email.com | 09222222222 | conductor | NULL | EMP-004 |

**Route_Table** (No change - already in 2NF)

| Route_ID | Route_Name | Start_Point | End_Point | Distance |
|----------|------------|-------------|-----------|----------|
| R001 | Downtown Express | North Terminal | City Center | 15.5 |
| R002 | Airport Shuttle | Airport | Downtown | 20.0 |

**Drop_Point_Table** (No change - already in 2NF)

| Drop_Point_ID | Trip_ID | Passenger_ID | Address | Sequence_Order | Status |
|---------------|---------|--------------|---------|----------------|--------|
| DP001 | T001 | P001 | 123 Main St | 1 | Requested |
| DP002 | T001 | P002 | 456 Oak Ave | 2 | Confirmed |
| DP003 | T002 | P003 | 789 Pine Rd | 1 | Completed |

**Explanation:**

All tables are now in second normal form because every non-key attribute is fully dependent on the primary key. In the Trip_Table, all attributes except foreign keys are fully dependent on Trip_ID. The Bus_Table has all attributes fully dependent on Bus_ID, the User_Table has all attributes fully dependent on User_ID, the Route_Table has all attributes fully dependent on Route_ID, and the Drop_Point_Table has all attributes fully dependent on Drop_Point_ID. Additionally, the Driver and Conductor tables were combined into a single Users table with a Role attribute, which eliminates redundancy since both drivers and conductors share similar attributes. This consolidation ensures that no partial dependencies exist, meaning no attribute depends on only part of a composite key.

**Problems Remaining:**

While second normal form eliminates partial dependencies, transitive dependencies may still exist in some tables. These occur when non-key attributes depend on other non-key attributes rather than directly on the primary key, which violates third normal form requirements and can lead to update anomalies.

---

### 3NF (Third Normal Form)

**Rule:** Remove transitive dependencies - no non-key attribute should depend on another non-key attribute.

**Trip_Table** (Final 3NF)

| Trip_ID | Schedule_ID | Bus_Assignment_ID | Trip_Date | Actual_Departure_Time | Actual_Arrival_Time | Passenger_Count | Status |
|---------|-------------|-------------------|-----------|----------------------|---------------------|-----------------|--------|
| T001 | S001 | BA001 | 2025-11-20 | 08:05 | 09:10 | 25 | In Progress |
| T002 | S002 | BA002 | 2025-11-20 | 10:02 | 11:35 | 30 | Completed |

**Schedule_Table**

| Schedule_ID | Bus_ID | Route_ID | Departure_Time | Arrival_Time | Day_of_Week | Is_Recurring | Effective_Date | End_Date | Status |
|-------------|--------|----------|----------------|--------------|-------------|--------------|---------------|----------|--------|
| S001 | B001 | R001 | 08:00 | 09:00 | Monday | true | 2025-11-01 | NULL | Scheduled |
| S002 | B002 | R002 | 10:00 | 11:30 | Monday | true | 2025-11-01 | NULL | Scheduled |

**Bus_Assignment_Table**

| Bus_Assignment_ID | Bus_ID | Driver_ID | Conductor_ID | Assignment_Date | Status |
|-------------------|--------|-----------|--------------|-----------------|--------|
| BA001 | B001 | D001 | C001 | 2025-11-20 | Active |
| BA002 | B002 | D002 | C002 | 2025-11-20 | Active |

**Bus_Table** (Final 3NF)

| Bus_ID | Bus_Number | License_Plate | Capacity | Model | Color | Status | Features |
|--------|------------|---------------|----------|-------|-------|--------|----------|
| B001 | YBL-001 | ABC-1234 | 50 | Hyundai | Blue | Active | AC, WiFi |
| B002 | YBL-002 | XYZ-5678 | 40 | Toyota | Red | Active | AC |

**User_Table** (Final 3NF)

| User_ID | Name | Email | Password | Role | Phone | Address | License_Number | Employee_ID | Is_Active |
|---------|------|-------|----------|------|-------|---------|----------------|-------------|-----------|
| D001 | John Doe | john@email.com | hashed | driver | 09123456789 | 123 St | DL-12345 | EMP-001 | true |
| C001 | Jane Smith | jane@email.com | hashed | conductor | 09111111111 | 456 Ave | NULL | EMP-003 | true |

**Route_Table** (Final 3NF)

| Route_ID | Route_Name | Description | Start_Point | End_Point | Start_Latitude | Start_Longitude | End_Latitude | End_Longitude | Distance | Estimated_Duration | Is_Active |
|----------|------------|------------|-------------|-----------|----------------|-----------------|--------------|---------------|----------|---------------------|-----------|
| R001 | Downtown Express | Fast route | North Terminal | City Center | 6.11640000 | 25.17160000 | 6.12000000 | 25.18000000 | 15.5 | 45 | true |
| R002 | Airport Shuttle | Airport route | Airport | Downtown | 6.13000000 | 25.19000000 | 6.12000000 | 25.18000000 | 20.0 | 60 | true |

**Drop_Point_Table** (Final 3NF)

| Drop_Point_ID | Trip_ID | Passenger_ID | Address | Latitude | Longitude | Sequence_Order | Status | Requested_At | Forwarded_At | Confirmed_At | Completed_At |
|---------------|---------|--------------|---------|----------|-----------|----------------|--------|--------------|--------------|--------------|--------------|
| DP001 | T001 | P001 | 123 Main St | 6.11650000 | 25.17170000 | 1 | Requested | 2025-11-20 08:10 | NULL | NULL | NULL |
| DP002 | T001 | P002 | 456 Oak Ave | 6.11700000 | 25.17200000 | 2 | Confirmed | 2025-11-20 08:15 | 2025-11-20 08:20 | 2025-11-20 08:25 | NULL |
| DP003 | T002 | P003 | 789 Pine Rd | 6.11800000 | 25.17300000 | 1 | Completed | 2025-11-20 10:05 | 2025-11-20 10:10 | 2025-11-20 10:15 | 2025-11-20 11:00 |

**Explanation:**

The Trip_Table achieved third normal form by removing all transitive dependencies. This was accomplished by separating schedule information into a dedicated Schedule_Table and separating bus assignment information into a Bus_Assignment_Table. The Trip table now references Schedule_ID and Bus_Assignment_ID instead of directly referencing Bus, Driver, Conductor, and Route entities. The Schedule_Table was created to store scheduled departure and arrival times separately from actual trip execution, allowing the system to distinguish between planned schedules and actual trip performance. The Bus_Assignment_Table was created to manage the relationship between buses, drivers, and conductors in a normalized way. As a result, all non-key attributes now depend only on the primary key and not on other non-key attributes, and all transitive dependencies have been eliminated.

**Benefits of 3NF:**

The third normal form provides significant benefits to the database structure. There is no data redundancy, as each fact is stored only once in the appropriate table. Update anomalies are eliminated, meaning changes to data only need to be made in one location and will be reflected throughout the system. Insert anomalies are removed, allowing new entities to be added independently without requiring related records to exist first. Delete anomalies are eliminated, ensuring that deleting one record does not inadvertently remove important information from other entities. The normalized structure provides efficient storage utilization and makes the database easier to maintain and modify over time.

---

## User and Bus Assignment Normalization

### UNF (Unnormalized Form)

**User_Bus_Assignment_Table**

| User_ID | User_Name | User_Email | User_Role | Bus_Number | Bus_Model | Assignment_Date | Assignment_Status | Driver_License | Employee_ID |
|---------|-----------|------------|-----------|------------|-----------|------------------|-------------------|----------------|-------------|
| U001 | John Doe | john@email.com | driver | YBL-001, YBL-002 | Hyundai, Toyota | 2025-11-20, 2025-11-21 | Active, Active | DL-12345 | EMP-001 |
| U002 | Jane Smith | jane@email.com | conductor | YBL-001 | Hyundai | 2025-11-20 | Active | NULL | EMP-003 |

**Problems in UNF:**

The unnormalized form suffers from several database design problems. Repeating groups exist where multiple bus assignments are stored in a single row, with bus numbers and models listed as comma-separated values. This creates data redundancy where bus information is repeated for each user assignment record. The structure leads to update anomalies where modifying bus information would require updating multiple assignment records. Insert and delete anomalies are present, making it difficult to add new buses or users without creating assignment records, and potentially losing important data when deleting assignment records.

---

### 1NF (First Normal Form)

**User_Table**

| User_ID | User_Name | User_Email | User_Role | Driver_License | Employee_ID |
|---------|-----------|------------|-----------|----------------|-------------|
| U001 | John Doe | john@email.com | driver | DL-12345 | EMP-001 |
| U002 | Jane Smith | jane@email.com | conductor | NULL | EMP-003 |

**Bus_Assignment_Table**

| Assignment_ID | User_ID | Bus_ID | Assignment_Date | Assignment_Status |
|---------------|---------|--------|------------------|-------------------|
| A001 | U001 | B001 | 2025-11-20 | Active |
| A002 | U001 | B002 | 2025-11-21 | Active |
| A003 | U002 | B001 | 2025-11-20 | Active |

**Bus_Table**

| Bus_ID | Bus_Number | Bus_Model | Status |
|--------|------------|-----------|--------|
| B001 | YBL-001 | Hyundai | Active |
| B002 | YBL-002 | Toyota | Active |

**Explanation:**

The first normal form was achieved by eliminating repeating groups through separating multiple bus assignments into individual rows, where each assignment is stored as a distinct record. Separate tables were created for Users and Buses to eliminate the redundancy of storing bus information repeatedly for each user assignment. All attributes are now atomic, meaning each field contains a single, indivisible value with no composite or multi-valued data.

---

### 2NF (Second Normal Form)

**User_Table** (No change - already in 2NF)

| User_ID | User_Name | User_Email | User_Role | Driver_License | Employee_ID |
|---------|-----------|------------|-----------|----------------|-------------|
| U001 | John Doe | john@email.com | driver | DL-12345 | EMP-001 |
| U002 | Jane Smith | jane@email.com | conductor | NULL | EMP-003 |

**Bus_Assignment_Table** (Composite key: User_ID + Bus_ID + Assignment_Date)

| Assignment_ID | Bus_ID | Driver_ID | Conductor_ID | Assignment_Date | Status |
|---------------|--------|-----------|--------------|-----------------|--------|
| A001 | B001 | U001 | U002 | 2025-11-20 | Active |
| A002 | B002 | U001 | NULL | 2025-11-21 | Active |

**Bus_Table** (No change - already in 2NF)

| Bus_ID | Bus_Number | Bus_Model | Capacity | Status |
|--------|------------|-----------|----------|--------|
| B001 | YBL-001 | Hyundai | 50 | Active |
| B002 | YBL-002 | Toyota | 40 | Active |

**Explanation:**

The Bus_Assignment_Table now properly represents the relationship between Bus, Driver, and Conductor entities, ensuring that the assignment information is stored in a normalized structure. All attributes are fully dependent on the primary key, meaning that every non-key attribute requires the complete primary key to be uniquely identified. No partial dependencies exist, which means no attribute depends on only a portion of a composite key.

---

### 3NF (Third Normal Form)

**User_Table** (Final 3NF)

| User_ID | Name | Email | Password | Role | Phone | Address | License_Number | Employee_ID | Is_Active |
|---------|------|-------|----------|------|-------|---------|----------------|-------------|-----------|
| U001 | John Doe | john@email.com | hashed | driver | 09123456789 | 123 St | DL-12345 | EMP-001 | true |
| U002 | Jane Smith | jane@email.com | hashed | conductor | 09111111111 | 456 Ave | NULL | EMP-003 | true |

**Bus_Assignment_Table** (Final 3NF)

| Bus_Assignment_ID | Bus_ID | Driver_ID | Conductor_ID | Assignment_Date | Status | Notes |
|-------------------|--------|-----------|--------------|-----------------|--------|-------|
| BA001 | B001 | U001 | U002 | 2025-11-20 | Active | Regular assignment |
| BA002 | B002 | U001 | NULL | 2025-11-21 | Active | Driver only |

**Bus_Table** (Final 3NF)

| Bus_ID | Bus_Number | License_Plate | Capacity | Model | Color | Status | Features |
|--------|------------|---------------|----------|-------|-------|--------|----------|
| B001 | YBL-001 | ABC-1234 | 50 | Hyundai | Blue | Active | AC, WiFi |
| B002 | YBL-002 | XYZ-5678 | 40 | Toyota | Red | Active | AC |

**Explanation:**

All transitive dependencies have been removed from the database structure. User information is now completely independent of bus assignments, meaning user data can be modified without affecting assignment records. Similarly, bus information is independent of assignments, allowing bus data to be updated without impacting assignment records. The assignment table only contains foreign keys that reference other tables and assignment-specific attributes such as assignment date and status. Most importantly, no non-key attribute depends on another non-key attribute, ensuring that all data dependencies flow directly through the primary key.

---

## Drop Point Management Normalization

### UNF (Unnormalized Form)

**Drop_Point_Management_Table**

| Trip_ID | Bus_Number | Driver_Name | Passenger_Name | Passenger_Email | Drop_Address | Drop_Latitude | Drop_Longitude | Request_Time | Forward_Time | Confirm_Time | Complete_Time | Status |
|---------|------------|-------------|----------------|-----------------|--------------|---------------|----------------|--------------|--------------|--------------|---------------|--------|
| T001 | YBL-001 | John Doe | Alice Passenger | alice@email.com | 123 Main St | 6.1165 | 25.1717 | 08:10 | 08:15 | 08:20 | NULL | Confirmed |
| T001 | YBL-001 | John Doe | Bob Passenger | bob@email.com | 456 Oak Ave | 6.1170 | 25.1720 | 08:12 | 08:17 | 08:22 | 09:00 | Completed |

**Problems in UNF:**

The unnormalized form contains significant data redundancy where trip, bus, and driver information is repeated for each drop point record. This redundancy leads to update anomalies where changing trip or driver information would require updating multiple drop point records. Insert and delete anomalies exist, making it difficult to manage drop points independently of trip and passenger data, and potentially causing data loss when records are deleted.

---

### 1NF (First Normal Form)

**Drop_Point_Table**

| Drop_Point_ID | Trip_ID | Passenger_ID | Address | Latitude | Longitude | Sequence_Order | Status | Requested_At | Forwarded_At | Confirmed_At | Completed_At |
|---------------|---------|--------------|---------|----------|-----------|----------------|--------|--------------|--------------|--------------|--------------|
| DP001 | T001 | P001 | 123 Main St | 6.1165 | 25.1717 | 1 | Confirmed | 08:10 | 08:15 | 08:20 | NULL |
| DP002 | T001 | P002 | 456 Oak Ave | 6.1170 | 25.1720 | 2 | Completed | 08:12 | 08:17 | 08:22 | 09:00 |

**Trip_Table**

| Trip_ID | Bus_ID | Driver_ID | Route_ID | Trip_Date | Status |
|---------|--------|-----------|----------|-----------|--------|
| T001 | B001 | D001 | R001 | 2025-11-20 | In Progress |

**Passenger_Table**

| Passenger_ID | Passenger_Name | Passenger_Email | Phone |
|--------------|----------------|-----------------|-------|
| P001 | Alice Passenger | alice@email.com | 09111111111 |
| P002 | Bob Passenger | bob@email.com | 09222222222 |

**Explanation:**

The normalization process separated drop point information from trip and passenger information, creating distinct tables for each entity. This separation ensures that drop point data is stored independently and can be managed without affecting trip or passenger records. All attributes are atomic, containing single, indivisible values. The separation also eliminated redundancy by storing trip and passenger information only once in their respective tables, rather than repeating this data for each drop point record.

---

### 2NF (Second Normal Form)

**Drop_Point_Table** (Already in 2NF)

| Drop_Point_ID | Trip_ID | Passenger_ID | Address | Latitude | Longitude | Sequence_Order | Status | Requested_At | Forwarded_At | Confirmed_At | Completed_At |
|---------------|---------|--------------|---------|----------|-----------|----------------|--------|--------------|--------------|--------------|--------------|
| DP001 | T001 | P001 | 123 Main St | 6.11650000 | 25.17170000 | 1 | Confirmed | 2025-11-20 08:10 | 2025-11-20 08:15 | 2025-11-20 08:20 | NULL |
| DP002 | T001 | P002 | 456 Oak Ave | 6.11700000 | 25.17200000 | 2 | Completed | 2025-11-20 08:12 | 2025-11-20 08:17 | 2025-11-20 08:22 | 2025-11-20 09:00 |

**Explanation:**

The Drop_Point_Table is in second normal form because all attributes are fully dependent on Drop_Point_ID, which serves as the primary key. This means that every attribute in the table requires the complete Drop_Point_ID to be uniquely identified and cannot be determined by only a portion of a composite key. No partial dependencies exist in this table structure.

---

### 3NF (Third Normal Form)

**Drop_Point_Table** (Final 3NF)

| Drop_Point_ID | Trip_ID | Passenger_ID | Address | Latitude | Longitude | Sequence_Order | Status | Requested_At | Forwarded_At | Confirmed_At | Completed_At | Notes |
|---------------|---------|--------------|---------|----------|-----------|----------------|--------|--------------|--------------|--------------|--------------|-------|
| DP001 | T001 | P001 | 123 Main St | 6.11650000 | 25.17170000 | 1 | Confirmed | 2025-11-20 08:10 | 2025-11-20 08:15 | 2025-11-20 08:20 | NULL | NULL |
| DP002 | T001 | P002 | 456 Oak Ave | 6.11700000 | 25.17200000 | 2 | Completed | 2025-11-20 08:12 | 2025-11-20 08:17 | 2025-11-20 08:22 | 2025-11-20 09:00 | NULL |

**User_Table** (Passenger is part of Users table)

| User_ID | Name | Email | Password | Role | Phone | Address |
|---------|------|-------|----------|------|-------|---------|
| P001 | Alice Passenger | alice@email.com | hashed | passenger | 09111111111 | 789 St |
| P002 | Bob Passenger | bob@email.com | hashed | passenger | 09222222222 | 321 Ave |

**Trip_Table** (References Schedule and Bus Assignment)

| Trip_ID | Schedule_ID | Bus_Assignment_ID | Trip_Date | Actual_Departure_Time | Actual_Arrival_Time | Passenger_Count | Status |
|---------|-------------|-------------------|-----------|----------------------|---------------------|-----------------|--------|
| T001 | S001 | BA001 | 2025-11-20 | 08:05 | NULL | 25 | In Progress |

**Explanation:**

All transitive dependencies have been removed from the drop point management structure. The drop point table now only contains drop point-specific attributes such as address, coordinates, sequence order, status, and timestamps, without any redundant information about passengers or trips. Passenger information is stored in the Users table, eliminating redundancy since passenger data is maintained in a single location. Trip information is properly normalized through the Schedule and Bus Assignment tables, ensuring that trip data flows through appropriate relationships rather than being duplicated in the drop point table.

---

## Schedule and Route Normalization

### UNF (Unnormalized Form)

**Schedule_Route_Table**

| Schedule_ID | Bus_Number | Bus_Model | Route_Name | Start_Point | End_Point | Distance | Departure_Time | Arrival_Time | Day_of_Week | Effective_Date |
|-------------|------------|-----------|------------|-------------|-----------|----------|----------------|--------------|-------------|----------------|
| S001 | YBL-001 | Hyundai | Downtown Express | North Terminal | City Center | 15.5 | 08:00 | 09:00 | Monday, Wednesday, Friday | 2025-11-01 |
| S002 | YBL-002 | Toyota | Airport Shuttle | Airport | Downtown | 20.0 | 10:00 | 11:30 | Daily | 2025-11-01 |

**Problems in UNF:**

The unnormalized form contains repeating groups where the Day_of_Week field has multiple values stored in a single cell, such as "Monday, Wednesday, Friday" or "Daily". This violates the atomicity requirement for first normal form. Additionally, there is significant data redundancy where bus and route information is repeated for each schedule entry, creating maintenance challenges and potential inconsistencies.

---

### 1NF (First Normal Form)

**Schedule_Table**

| Schedule_ID | Bus_ID | Route_ID | Departure_Time | Arrival_Time | Day_of_Week | Effective_Date | End_Date | Status |
|-------------|--------|----------|----------------|--------------|-------------|-----------------|----------|--------|
| S001 | B001 | R001 | 08:00 | 09:00 | Monday | 2025-11-01 | NULL | Scheduled |
| S002 | B001 | R001 | 08:00 | 09:00 | Wednesday | 2025-11-01 | NULL | Scheduled |
| S003 | B001 | R001 | 08:00 | 09:00 | Friday | 2025-11-01 | NULL | Scheduled |
| S004 | B002 | R002 | 10:00 | 11:30 | Monday | 2025-11-01 | NULL | Scheduled |
| S005 | B002 | R002 | 10:00 | 11:30 | Tuesday | 2025-11-01 | NULL | Scheduled |
| S006 | B002 | R002 | 10:00 | 11:30 | Wednesday | 2025-11-01 | NULL | Scheduled |
| S007 | B002 | R002 | 10:00 | 11:30 | Thursday | 2025-11-01 | NULL | Scheduled |
| S008 | B002 | R002 | 10:00 | 11:30 | Friday | 2025-11-01 | NULL | Scheduled |
| S009 | B002 | R002 | 10:00 | 11:30 | Saturday | 2025-11-01 | NULL | Scheduled |
| S010 | B002 | R002 | 10:00 | 11:30 | Sunday | 2025-11-01 | NULL | Scheduled |

**Route_Table**

| Route_ID | Route_Name | Start_Point | End_Point | Distance | Estimated_Duration |
|----------|------------|-------------|-----------|----------|---------------------|
| R001 | Downtown Express | North Terminal | City Center | 15.5 | 45 |
| R002 | Airport Shuttle | Airport | Downtown | 20.0 | 60 |

**Bus_Table**

| Bus_ID | Bus_Number | Bus_Model | Capacity | Status |
|--------|------------|-----------|----------|--------|
| B001 | YBL-001 | Hyundai | 50 | Active |
| B002 | YBL-002 | Toyota | 40 | Active |

**Explanation:**

The first normal form was achieved by eliminating repeating groups through creating separate rows for each day of the week, rather than storing multiple days in a single field. This ensures that each schedule record represents one specific day and time combination. Route and bus information were separated into their own tables to eliminate redundancy, as this information was being repeated for each schedule entry. All attributes are now atomic, containing single, indivisible values with no composite or multi-valued data.

---

### 2NF (Second Normal Form)

**Schedule_Table** (Composite key considerations)

| Schedule_ID | Bus_ID | Route_ID | Departure_Time | Arrival_Time | Day_of_Week | Is_Recurring | Effective_Date | End_Date | Status |
|-------------|--------|----------|----------------|--------------|-------------|--------------|-----------------|----------|--------|
| S001 | B001 | R001 | 08:00 | 09:00 | Monday | true | 2025-11-01 | NULL | Scheduled |
| S002 | B002 | R002 | 10:00 | 11:30 | Monday | true | 2025-11-01 | NULL | Scheduled |

**Explanation:**

The Schedule_Table is in second normal form because all attributes are fully dependent on Schedule_ID, which serves as the primary key. This means that every attribute requires the complete Schedule_ID to be uniquely identified. No partial dependencies exist, ensuring that no attribute depends on only a portion of a composite key. The Is_Recurring field was added to indicate whether a schedule repeats weekly, which simplifies the management of daily schedules by allowing the system to distinguish between one-time schedules and recurring weekly schedules.

---

### 3NF (Third Normal Form)

**Schedule_Table** (Final 3NF)

| Schedule_ID | Bus_ID | Route_ID | Departure_Time | Arrival_Time | Day_of_Week | Is_Recurring | Effective_Date | End_Date | Status |
|-------------|--------|----------|----------------|--------------|-------------|--------------|-----------------|----------|--------|
| S001 | B001 | R001 | 08:00 | 09:00 | Monday | true | 2025-11-01 | NULL | Scheduled |
| S002 | B002 | R002 | 10:00 | 11:30 | Monday | true | 2025-11-01 | NULL | Scheduled |

**Route_Table** (Final 3NF)

| Route_ID | Route_Name | Description | Start_Point | End_Point | Start_Latitude | Start_Longitude | End_Latitude | End_Longitude | Distance | Estimated_Duration | Waypoints | Is_Active |
|----------|------------|------------|-------------|-----------|----------------|-----------------|--------------|---------------|----------|---------------------|-----------|-----------|
| R001 | Downtown Express | Fast route | North Terminal | City Center | 6.11640000 | 25.17160000 | 6.12000000 | 25.18000000 | 15.5 | 45 | JSON | true |
| R002 | Airport Shuttle | Airport route | Airport | Downtown | 6.13000000 | 25.19000000 | 6.12000000 | 25.18000000 | 20.0 | 60 | JSON | true |

**Bus_Table** (Final 3NF)

| Bus_ID | Bus_Number | License_Plate | Capacity | Model | Color | Status | Features |
|--------|------------|---------------|----------|-------|-------|--------|----------|
| B001 | YBL-001 | ABC-1234 | 50 | Hyundai | Blue | Active | AC, WiFi |
| B002 | YBL-002 | XYZ-5678 | 40 | Toyota | Red | Active | AC |

**Explanation:**

All transitive dependencies have been removed from the schedule and route structure. The schedule table now only contains schedule-specific attributes such as departure time, arrival time, day of week, and effective dates, along with foreign keys that reference the Bus and Route tables. Route and Bus information is completely independent, meaning route data can be modified without affecting schedule records, and bus data can be updated without impacting schedules. Most importantly, no non-key attribute depends on another non-key attribute, ensuring that all data dependencies flow directly through the primary key rather than through intermediate attributes.

---

## Summary of Normalization Benefits

### Before Normalization (UNF)

Before normalization, the database suffered from high data redundancy where the same data was stored multiple times across different records. Update anomalies were prevalent, requiring changes to bus models to be made across all trip records that referenced that bus. Insert anomalies existed because it was impossible to add a new bus to the system without first creating a trip record. Delete anomalies were present where deleting a trip record might inadvertently delete important bus information. The unnormalized structure resulted in storage inefficiency with wasted storage space, and created a significant risk of data inconsistency where the same information might be stored differently in various locations.

### After Normalization (3NF)

After normalization to third normal form, data redundancy has been minimized with each fact stored only once in the appropriate table. Update anomalies have been completely eliminated, allowing updates to be made in a single location with changes automatically reflected throughout the system. Insert anomalies are eliminated, enabling entities to be added independently without requiring related records to exist first. Delete anomalies are removed, ensuring that deleting one entity does not affect other unrelated entities. The normalized structure provides optimized storage usage, guarantees data consistency through foreign key relationships, makes the database easier to maintain and modify, and improves query performance through proper indexing strategies.

---

## Final Database Structure (3NF)

The normalized database consists of the following main tables:

1. **users** - Stores all user information (admin, supervisor, driver, conductor, passenger)
2. **buses** - Stores bus fleet information
3. **bus_assignments** - Links buses with drivers and conductors
4. **routes** - Stores route definitions
5. **schedules** - Stores scheduled bus departures
6. **trips** - Stores actual trip executions
7. **drop_points** - Stores passenger drop point requests
8. **bus_locations** - Stores real-time GPS location data
9. **bus_logs** - Stores activity logs
10. **notifications** - Stores user notifications
11. **driver_issues** - Stores driver-reported issues

All tables are in Third Normal Form (3NF), ensuring that there are no repeating groups, no partial dependencies, and no transitive dependencies. This normalization provides optimal data organization and maximum data integrity throughout the entire database system.

