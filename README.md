# Restaurant Management System (MVC, PHP + MySQL)

A full-featured restaurant management system built with **pure PHP and MySQL**, following a clean **MVC architecture**.  
The system supports three main user roles: **Staff, Admin, and Customers**.  

-  **Staff** can manage orders, update statuses, and interact with a real-time kitchen dashboard.  
-  **Admins** can configure menus, manage staff, see order analytics and calculate revenue.  
-  **Customers** can place orders, receive email notifications, and track progress.  

This project was designed to simulate a real-world restaurant workflow with 
**secure backend logic**, 
**responsive UI**, and 
**role-based access control**.


##  Features

### Authentication & Security
- Secure login for **Staff** and **Admin**
- Passwords stored with `password_hash()` and verified with `password_verify()`
- CSRF tokens for form submissions
- `htmlspecialchars()` for XSS prevention
- Session-gated admin routes

###  Customer Features
- Place new orders with itemized details
- Receive branded email notifications when orders are ready
- Track order status in real time
- No duplicate accounts

###  Staff Features
- Secure login with staff credentials
- Kitchen dashboard with **live order updates** (JavaScript polling / WebSockets)
- Update order statuses (New → In Progress → Ready)
- Add kitchen notes for clarity
- Responsive interface for tablets and kitchen displays

###  Admin Features
- Manage menus, staff accounts, and restaurant settings
- Edit or delete orders with full control
- Publish/Unpublish menu items dynamically
- Access full customer order history
- Maintain restaurant structure with dynamic routing

###  Email Notifications
- Branded, styled templates with customizable background colors
- Robust error handling and logging
- Automated flow triggered when orders are marked ready


##  Project Setup

1. **Copy project folder**  
   Paste the folder `restaurant_management_system` into your `htdocs/` directory.

2. **Open in IDE**  
   Open the folder in VS Code or your preferred IDE.

3. **Create Database**  
   In phpMyAdmin, create a new database named:

   (Name must match exactly.)

4. **Import SQL**  
Import the SQL file located at:


5. **Run the Project**  
In your browser, visit:

## 📁 Project Structure
restaurant_management_system/ 
├── app/    # Controllers, Models, Views
├── config/  # Database and email config
├── database/  # SQL dump 
├── public/    # Assets (CSS, JS, images) 
├── storage/    # Logs and uploads
├── index.php    # Entry point and router 
└── README.md     # Setup and documentation



##  UI & Accessibility

- Responsive design for desktop, tablet, and kitchen displays
- Color-coded order statuses (New, In Progress, Ready)
- Accessible forms and navigation
- Dark mode and high-contrast options



##  Team Contributions

- **Tirsana** – Database Designer & Customer Interface Developer  
-**Sanjana** – System Designer & Staff Interface Developer  
- **Nusrat** – Admin Interface Developer  



##  License

This project is for **academic purposes** and is distributed under the MIT License.  
See `LICENSE` for details.