# Parent Supporting System for an Autistic Child

## Project Overview
The **Parent Supporting System for an Autistic Child** is a **web-based application** designed to empower parents, caregivers, and educators with tools and insights to support children with autism effectively.

This system combines **social interaction, educational resources, behavior tracking, and planning tools** in a single, easy-to-use platform.

It aims to improve **parent engagement, behavior monitoring, and learning development** for autistic children.

---

## Features

### Core Features

**Newsfeed & Social Interaction**  
- Posts, comments, likes, and discussions with other parents.

**Search Functionality**  
- Quickly find resources, articles, or community discussions.

**Notifications**  
- Stay updated with new posts, events, and resources.

**Behavior Tracking System**  
- Record and analyze your child’s behavioral patterns.

**Daily Planner**  
- Schedule activities and track daily routines.

**Events Management**  
- Create, join, and track events for learning and therapy.

**Educational Resources**  
- Access curated content for cognitive and behavioral development.

**Messaging System**  
- Communicate privately with other parents or experts.

**Reports & Analytics**  
- Generate reports to monitor progress and trends.

---

## Project Structure

project-root/
│
├─ assets/ # CSS, JS, images, icons/n
├─ auth/ # User authentication (login, registration)\n
├─ config/ # Database connection and common functions
│ ├─ config.php
│ └─ function.php
├─ database/ # SQL dump for database setup
│ └─ autism_support_system_(7).sql
├─ modules/ # Functional modules
│ ├─ social/ # Newsfeed, profile, therapy logs
│ ├─ planner/ # Daily planner, events
│ ├─ reports/ # Behavioral analysis, patterns, Q&A, resources
│ └─ behavior/ # Behavioral tracking and analysis
├─ includes/ # Header, footer, and common templates
│ └─ header.php
├─ index.php # Landing page
└─ README.md # Project documentation


---

## UI Design
- **Navbar:** Fixed, gradient-colored, with search and notification icons.  
- **Dropdown Menus:** Quick access to profile, logout, and other features.  
- **Color Scheme:** Calming and friendly for parents, using gradients and soft colors.  
- **Responsive Layout:** Optimized for mobile and desktop screens.  

---

## Technology Stack
- **Frontend:** HTML5, CSS3, JavaScript  
- **Backend:** PHP  
- **Database:** MySQL (structured tables for users, posts, events, and reports)  
- **Server Environment:** XAMPP (Apache + MySQL)  

---

## Modules Description

### Social Module
- **Newsfeed:** Interact with other parents, share posts, and like/comment.  
- **Profile Management:** Update personal info and child’s data.  
- **Log Therapy:** Track and maintain therapy sessions.  

### Planner Module
- **Daily Planner:** Schedule tasks and monitor daily routines.  
- **Events:** Join or create educational and social events.  

### Reports Module
- **Behavior Analysis:** Evaluate behavioral patterns and track progress.  
- **Q&A:** Post questions and receive advice from experts.  
- **Resources:** Access educational content for children’s development.  

---

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/sayma-sultana-isra/iamspecial.com.git
cd iamspecial.com
2.Database Setup

Open phpMyAdmin and create a new database.

Import database/autism_support_system_(7).sql.

3. Configure Database Connection

Update config/config.php with your MySQL credentials.

4. Run the Project

Start XAMPP (Apache + MySQL).

Open http://localhost/project-root/ in your browser.

5. Register / Login

Use the registration module to create your first account and explore features.

Contributing

We welcome contributions from developers, parents, and educators!

Fork the repository

Create a feature branch

Submit a pull request

Contact







Developer: Sayma Sultana

Email: saymasultana@example.com

GitHub: github.com/sayma-sultana-isra
