# CareQueue

CareQueue is a hospital queue management system built with Laravel to simplify patient flow and improve how hospital departments manage queues.

The system allows patients to join queues digitally while giving receptionists, doctors, and administrators the tools they need to manage patients and hospital queue operations.

## Screenshots

### Patient Department Selection

![Patient Department Selection](screenshots/patient-home.png)


### Receptionist Dashboard

![Receptionist Dashboard](screenshots/Receptionist-dashboard.png)

### Doctor Dashboard

![Doctor Dashboard](screenshots/Doctor-dashboard.png)

### Admin Dashboard

![Admin Dashboard](screenshots/Admin-dashboard.png)

## Features

* Patient registration and authentication
* Department selection
* Digital queue number generation
* Patient queue tracking
* Queue position management
* Receptionist queue management
* Doctor patient management
* Role-based access control
* Administrator management
* Patient status tracking
* Secure authentication
* Responsive user interface

## User Roles

### Patient

Patients can:

* Register and log in to the system
* Select a hospital department
* Join a department queue
* Receive a queue number
* View their position in the queue
* Track their queue status

### Receptionist

Receptionists can:

* View patients waiting in queues
* Manage department queues
* Call the next patient
* Update patient queue status
* Manage the flow of patients through the queue

### Doctor

Doctors can:

* View patients waiting to be attended to
* View their assigned queue
* Manage patients being attended to
* Update patient status as they progress through the queue

### Administrator

Administrators can:

* Manage system users
* Manage hospital departments
* Monitor queue activity
* Manage system operations
* Access administrative functionality

## How It Works

1. A patient logs into CareQueue.
2. The patient selects the department they need.
3. CareQueue assigns the patient a queue number.
4. The patient can monitor their position in the queue.
5. Receptionists manage patients waiting in each department.
6. Doctors attend to patients as they reach the front of the queue.
7. Patient queue statuses are updated throughout the process.

## Tech Stack

* **Framework:** Laravel
* **Language:** PHP
* **Database:** MySQL
* **Frontend:** Blade, HTML, CSS, JavaScript
* **Styling:** Tailwind CSS
* **Authentication:** Laravel Breeze
* **Development Environment:** XAMPP

## Project Structure

The application follows Laravel's MVC architecture.

```text
app/
├── Http/
│   ├── Controllers/
│   └── Middleware/
├── Models/
└── ...

database/
├── migrations/
└── seeders/

resources/
├── views/
├── css/
└── js/

routes/
└── web.php
```


## Live Demo

**CareQueue:** https://hospital-queue-zgwa.onrender.com

> Note: The live demo may occasionally be unavailable when its database hosting service is suspended.

## Purpose of the Project

CareQueue was developed as a practical Laravel project to solve a real-world problem in hospital operations: managing patient queues efficiently.

The project demonstrates the implementation of:

* Laravel MVC architecture
* Database relationships
* Authentication
* Role-based authorization
* CRUD operations
* Queue management logic
* Middleware
* Dynamic Blade views
* MySQL database management
* Responsive web application development

## Future Improvements

Potential future improvements include:

* Real-time queue updates
* SMS or email notifications
* Estimated waiting time
* Appointment scheduling
* Hospital analytics and reporting
* Multiple hospital branches
* Improved queue prioritization
* Mobile application support

## Author

**Fortune Charles**

Software Engineering Student & Backend Developer

Built with Laravel, PHP, MySQL, and a focus on solving practical problems through software.
