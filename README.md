# Web Programming Project
<img src="https://github.com/user-attachments/assets/15b4c936-4ad1-47c3-a8c0-1ae598436cb7" alt="Screenshot1" width="500" />
<img src="https://github.com/user-attachments/assets/db6ae3a5-eb00-4da5-8595-ec9e09e79c27" alt="Screenshot2" width="500" />

## Table of Contents!

- [Overview](#overview)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Project Setup](#project-setup)
- [Usage](#usage)
- [Database Structure](#database-structure)


---

## Overview

This project is a demonstration of implementing a web programming project for the **IT103** course. It focuses on creating a minimalistic platform where users can view, add, and comment on posts with an interactive and responsive interface.

---

## Features

- **User Login Interface**: A secure and user-friendly login system.
- **View Posts**: Displays all posts along with their associated comments.
- **Add New Posts**: Users can create posts through a simple form.
- **Commenting System**: Allows users to add comments to existing posts.
- **Responsive Design**: Adapts seamlessly across various devices.

---

## Technologies Used

- **Frontend**: 
  - HTML5, CSS3
  - Responsive design using custom CSS
- **Backend**:
  - PHP
- **Database**:
  - MySQL
- **Design**:
  - Interactive UI with gradients and a clean layout

---

## Project Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/mehdi-kbz/Vie-de-l-Enseirb.git
   ```
2. **Import the database**  
   Use the `code.sql` file provided in the project to set up the database schema and populate initial data.

3. **Configure the project**  
   Update the `config.php` file with your database credentials.

4. **Start a local PHP server**
   ```bash
   php -S localhost:8000
   ```

5. **Access the project**:
Open ```http://localhost:8000``` in your preferred web browser.


## Usage

1. **Login**  
   Use your username and password to access the system.

2. **View Posts**  
   Navigate to the posts page to see all posts and their respective comments.

3. **Add a New Post**  
   Click the **Add Post** button to create a new post using the form provided.

4. **Comment on Posts**  
   Use the comment form on a post page to add your comment.

---

## Database Structure

The database includes the following tables:

1. **Posts Table**  
   - `id`: Primary key  
   - `title`: Title of the post  
   - `content`: Content of the post  
   - `created_at`: Timestamp when the post was created

2. **Comments Table**  
   - `id`: Primary key  
   - `post_id`: Foreign key referencing the `Posts` table  
   - `comment`: Text of the comment  
   - `created_at`: Timestamp when the comment was created
