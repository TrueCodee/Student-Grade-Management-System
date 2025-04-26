# Student Grade Management System

---

## Overview
The **Student Grade Management System** is a secure web application developed using **PHP**, **MySQL**, and **HTML/CSS**. It enables authenticated users to view, search, update, and delete student grade records while ensuring data security through prepared statements and CSRF protection.

This project was created as part of the **CP476A - Internet Computing** course requirements.

---

## Key Features
- Secure MySQL login authentication
- Student records dashboard
- Real-time final grade calculation
- Grade validation (only allows values between 0–100)
- Search functionality by Student ID (prefix match) or exact Course Code
- Update and delete functionalities with security measures
- CSRF token validation for delete operations
- Database operations with prepared statements to prevent SQL injection
- Records sorted in ascending order by Student ID

---

## Technologies Used
- **Frontend:** HTML5, CSS3
- **Backend:** PHP 8+
- **Database:** MySQL 8+
- **Server:** Apache HTTP Server (manual setup, no XAMPP or WAMP)

---

## Database Design

### Table: `name_table`
| Column       | Type         | Description                   |
|--------------|--------------|-------------------------------|
| `student_id` | VARCHAR(10)  | Primary key, unique student ID |
| `student_name` | VARCHAR(50) | Full name of the student      |

### Table: `course_table`
| Column        | Type         | Description                            |
|---------------|--------------|----------------------------------------|
| `student_id`  | VARCHAR(10)  | Foreign key referencing `name_table`  |
| `course_code` | VARCHAR(10)  | Primary key, course identifier        |
| `test1`       | INT          | Test 1 score                          |
| `test2`       | INT          | Test 2 score                          |
| `test3`       | INT          | Test 3 score                          |
| `final_exam`  | INT          | Final exam score                      |

**Relationship:**  
Each record in `course_table` links back to a student in `name_table` through the `student_id` foreign key.

---

## How to Run the Project Locally
1. Clone or download the project repository.
2. Set up a MySQL database and create the required tables using the provided schema.
3. Place all PHP project files in your Apache `htdocs/` directory.
4. Start Apache and MySQL servers manually.
5. Access the project via `http://localhost/Project/login.php`.
6. Log in using your MySQL username and password.


---

## Security Implementation
- **Session Management:** Login required to access dashboard.
- **CSRF Protection:** All delete operations validated with CSRF tokens.
- **Input Validation:** Only valid grades between 0 and 100 are accepted.
- **SQL Injection Prevention:** Database operations utilize prepared statements.

---

## Demonstration Highlights
- ✅ Students can be searched easily using either partial Student IDs or exact course codes.
- ✅ Final grades update dynamically when modifying test or exam scores.
- ✅ Negative or invalid input is rejected with error messages.
- ✅ Deletion operations are secure and immediately reflected on the dashboard and database.

---

## Future Enhancements
- Add role-based user access (admin/student roles).
- Export student records as CSV or PDF files.
- Implement improved error handling and audit logs.

---



