# Music Producer Hub

A social platform for music producers to share their projects, samples, and connect with other producers.

## Features

- User authentication and profiles
- Project sharing and streaming
- Sample library management
- Social features (likes, comments, follows)
- Real-time chat system
- Project collaboration tools

## Tech Stack

- PHP 8.1+
- MySQL Database
- HTML5, CSS3, JavaScript
- Bootstrap 5 for responsive design
- WebSocket for real-time chat
- FFmpeg for audio processing

## Setup Instructions

1. Clone the repository
2. Set up a local PHP development environment (XAMPP/WAMP/MAMP)
3. Import the database schema from `database/schema.sql`
4. Configure database connection in `config/database.php`
5. Start the development server

## Directory Structure

```
music-producer-hub/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── config/
├── database/
├── includes/
├── uploads/
│   ├── projects/
│   └── samples/
└── vendor/
```

## Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- FFmpeg for audio processing
- Composer for PHP dependencies

## License

MIT License 