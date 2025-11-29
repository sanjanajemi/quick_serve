# Restaurant Management System (MVC, PHP + MySQL)

A full-featured restaurant management system built with **HTML, PHP, JavaScript and MySQL**, following a clean **MVC architecture** and **SoC**(Separation of Concerns).  
The system supports three main user roles: **Customer, Staff, Admin**.  

-  **Customers** can place orders, download receipts, receive email notifications, and track order progress.  
-  **Staff** can manage orders, place orders, cancel orders, update statuses, hide/show menu items and interact with a real-time kitchen dashboard.  
-  **Admins** can configure menus, manage staff, manage customers, add admins, see order analytics and calculate revenue.  


This project was designed to simulate a real-world restaurant workflow with 
**secure backend logic**, 
**responsive UI**, and 
**role-based access control**.


##  Features

### Authentication & Security
- Secure login for**Customer**, **Staff** and **Admin**
- Passwords stored with `password_hash()` and verified with `password_verify()`
- CSRF tokens for form submissions
- `htmlspecialchars()` for XSS prevention
- Front end + back end validation and sanitization.
- Session-required page access

###  Customer Features
- Register as a new customer in the system.
- Secure login with customer credentials
- Manage customer account.
- Place new orders with additional details and download receipt.
- Receive email notifications when orders are ready
- Track order status in real time
- No duplicate accounts

###  Staff Features
- Secure login with staff credentials
- Manage staff account with CRUD functionality.
- Hide and show menu items to customer's menu list.
- Kitchen dashboard with **live order updates** (JavaScript)
- Update order statuses (Not set→ Received→ Preparing → Ready → Send email)
- Sending order ready mail to customer via PhpMailer.
- Order cancellation + Auto order cancellation mail to customer through PhpMailer.
- Add Order: Staff can order on behalf of customer and a backup of customer interface crash.
- Responsive interface for tablets and kitchen displays

###  Admin Features
- Secure login with admin credentials.
- Manage admin account.
- Manage staff accounts, customer list and manage menu
- Create new admin and manage account.
- Hide/Show menu items dynamically to cutomers.
- Access full customer order history.
- View restaurant order analytics and revenue calculation.

###  Email Notifications for ready orders and cancelled orders.
- Automated flow triggered when orders are marked ready and pressed 'Send email' button.
- Auto cancellation mail sending feature when orders are cancelled by staff.


##  Project Setup

1. **Copy project folder**  
   Paste the folder `quick_serve` into your `htdocs/` directory.

2. **Open in IDE**  
   Open the folder 'quick_serve' in VS Code or your preferred IDE.

3. **Create Database**  
   In phpMyAdmin, create a new database named:'brock_cafe'

4. **Import SQL**  
Import the SQL file located at project root:'brock_cafe.sql'

5. **Run the Project**  
In your browser, visit: localhost/quick_serve

## 📁 Project Structure
quick_serve/ 
├── app/    # Controllers, Models, Views

├── config/  # Database and email config 

├── assets/    # (CSS, JS, images) 

├── storage/    # Logs and uploads

├── libs/ PhpMailer

├── bootstrap.php

├── index.php    # Entry point

└── README.md     # Setup and documentation



##  UI & Accessibility

- Responsive design for desktop, tablet, and kitchen displays
- Color-coded order statuses and order cards (Not set, Received, Preparing, Ready)
- Accessible forms and navigation
- Dark mode options.



##  Team Contributions

- **Tirsana** –Customer Interface Developer  
- **Sanjana** – System Designer & Staff Interface Developer (Scrum master) 
- **Nusrat** – Admin Interface Developer



##  License

This project is for **academic purposes** and is distributed under the MIT License.  
See `LICENSE` for details.
