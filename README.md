# PetrogenAI - Employee AI Chat Platform

A ChatGPT-like platform built with Laravel and OpenAI API, designed specifically for Petrogen employees. Features include real-time chat, file uploads, conversation management, and a modern Tailwind CSS interface.

## Features

- 🤖 **AI-Powered Chat**: Integrated with OpenAI API (GPT-4)
- 📎 **File Attachments**: Upload and discuss documents (PDF, DOC, TXT, images)
- 💬 **Conversation Management**: Create, view, and delete chat histories
- 🔐 **Authentication**: Secure login and registration system
- 👥 **Multi-User Support**: Employee and admin roles
- 🎨 **Modern UI**: Tailwind CSS with Metronic design principles
- 📱 **Responsive Design**: Works on desktop, tablet, and mobile

## Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Node.js 18 or higher
- npm or yarn
- Nginx or Apache web server

## Installation

### Local Development

1. **Clone the repository**
   ```bash
   cd /path/to/project
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   
   Update `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=petrogenai
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Add OpenAI API Key**
   
   Update `.env` file:
   ```
   OPENAI_API_KEY=your_openai_api_key_here
   OPENAI_MODEL=gpt-4-turbo-preview
   ```

7. **Run migrations**
   ```bash
   php artisan migrate
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Start development server**
   ```bash
   php artisan serve
   ```

   Visit `http://localhost:8000`

### Production Deployment (petrogen.ai)

1. **Upload files to server**
   ```bash
   # Upload all files to /var/www/petrogenai
   ```

2. **Run deployment script**
   ```bash
   chmod +x deploy.sh
   ./deploy.sh
   ```

3. **Configure database**
   ```sql
   CREATE DATABASE petrogenai;
   CREATE USER 'petrogenai'@'localhost' IDENTIFIED BY 'secure_password';
   GRANT ALL PRIVILEGES ON petrogenai.* TO 'petrogenai'@'localhost';
   FLUSH PRIVILEGES;
   ```

4. **Update environment variables**
   ```bash
   nano .env
   # Update DB credentials, APP_URL=https://petrogen.ai, and OPENAI_API_KEY
   ```

5. **Run migrations**
   ```bash
   php artisan migrate --force
   ```

6. **Install SSL certificate**
   ```bash
   sudo certbot --nginx -d petrogen.ai -d www.petrogen.ai
   ```

7. **Set up cron jobs** (optional, for scheduled tasks)
   ```bash
   crontab -e
   # Add: * * * * * cd /var/www/petrogenai && php artisan schedule:run >> /dev/null 2>&1
   ```

## Usage

### Creating an Account

1. Navigate to `/register`
2. Fill in your name, email, department, and password
3. Click "Create Account"

### Starting a Chat

1. Log in to your account
2. Click "New Chat" in the sidebar
3. Type your message in the input field
4. Optionally attach files using the paperclip icon
5. Press Enter or click the send button

### Managing Conversations

- **View**: Click on any conversation in the sidebar to view it
- **Delete**: Hover over a conversation and click the trash icon
- **New Chat**: Click "New Chat" to start a fresh conversation

### File Uploads

Supported file types:
- Documents: PDF, DOC, DOCX, TXT
- Images: JPG, JPEG, PNG
- Maximum file size: 10MB per file

## Configuration

### OpenAI Settings

Edit `.env` to customize OpenAI behavior:

```env
OPENAI_MODEL=gpt-4-turbo-preview  # or gpt-3.5-turbo for lower cost
OPENAI_MAX_TOKENS=2000             # Maximum tokens per response
```

### Security

- Change default `APP_KEY` in production
- Use strong database passwords
- Enable HTTPS (SSL certificate)
- Keep OpenAI API key secure
- Set `APP_DEBUG=false` in production

## Troubleshooting

### Common Issues

**Issue**: "CSRF token mismatch"
- Clear browser cache and cookies
- Check `APP_URL` in `.env` matches your domain

**Issue**: "OpenAI API error"
- Verify your API key is correct in `.env`
- Check your OpenAI account has available credits
- Ensure your API key has the right permissions

**Issue**: "File upload fails"
- Check `upload_max_filesize` in `php.ini`
- Verify storage directory permissions: `chmod -R 775 storage`

## Tech Stack

- **Backend**: Laravel 10
- **Frontend**: Tailwind CSS, Alpine.js
- **Database**: MySQL
- **AI**: OpenAI API (GPT-4)
- **Web Server**: Nginx
- **Asset Building**: Vite

## Security Considerations

- All user inputs are sanitized
- CSRF protection enabled
- SQL injection prevention via Eloquent ORM
- File upload validation and size limits
- Secure password hashing (bcrypt)
- HTTPS enforced in production

## License

Proprietary - © 2024 Petrogen. All rights reserved.

## Support

For support, contact your IT administrator or email: support@petrogen.ai

## Version

Current Version: 1.0.0
Last Updated: December 2024
