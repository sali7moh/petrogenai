# PetrogenAI - Complete Deployment Checklist

## 📋 Pre-Deployment Checklist

### Local Development Setup
- [ ] PHP 8.1 or higher installed
- [ ] Composer installed
- [ ] Node.js 18+ and npm installed
- [ ] MySQL installed and running
- [ ] Git installed (optional)
- [ ] Code editor (VS Code, PHPStorm, etc.)

### OpenAI Account Setup
- [ ] OpenAI account created
- [ ] API key generated
- [ ] Billing/credits configured
- [ ] API key saved securely

### Domain & Hosting
- [ ] Domain `petrogen.ai` registered
- [ ] Server/VPS provisioned (Ubuntu 20.04+ recommended)
- [ ] DNS A record pointing to server IP
- [ ] Root/sudo access to server
- [ ] SSH access configured

## 🔧 Local Installation Checklist

### Step 1: Dependencies
- [ ] Run `composer install` successfully
- [ ] Run `npm install` successfully
- [ ] No error messages during installation

### Step 2: Environment Configuration
- [ ] Copy `.env.example` to `.env`
- [ ] Run `php artisan key:generate`
- [ ] Set `APP_NAME=PetrogenAI`
- [ ] Set `APP_ENV=local`
- [ ] Set `APP_DEBUG=true`
- [ ] Set `APP_URL=http://localhost:8000`

### Step 3: Database Setup
- [ ] Create database: `CREATE DATABASE petrogenai;`
- [ ] Update `DB_DATABASE=petrogenai` in `.env`
- [ ] Update `DB_USERNAME` in `.env`
- [ ] Update `DB_PASSWORD` in `.env`
- [ ] Test database connection

### Step 4: OpenAI Configuration
- [ ] Add `OPENAI_API_KEY` to `.env`
- [ ] Set `OPENAI_MODEL=gpt-4-turbo-preview`
- [ ] Set `OPENAI_MAX_TOKENS=2000`
- [ ] Verify API key is valid

### Step 5: Build Assets
- [ ] Run `npm run build` successfully
- [ ] CSS files compiled in `public/build`
- [ ] JS files compiled in `public/build`
- [ ] No compilation errors

### Step 6: Database Migration
- [ ] Run `php artisan migrate`
- [ ] All migrations executed successfully
- [ ] Tables created: users, conversations, messages, attachments
- [ ] No migration errors

### Step 7: Testing Locally
- [ ] Run `php artisan serve`
- [ ] Visit `http://localhost:8000`
- [ ] Register page loads correctly
- [ ] Login page loads correctly
- [ ] Can create new account
- [ ] Can login with credentials
- [ ] Chat interface loads
- [ ] Can send message
- [ ] Receive AI response
- [ ] Can upload file
- [ ] Can create new conversation
- [ ] Can delete conversation
- [ ] Can logout

## 🌐 Production Deployment Checklist

### Phase 1: Server Preparation
- [ ] Server OS updated: `sudo apt update && sudo apt upgrade`
- [ ] Nginx installed
- [ ] MySQL installed and secured
- [ ] PHP 8.1-FPM installed
- [ ] Required PHP extensions installed
- [ ] Composer installed globally
- [ ] Node.js and npm installed
- [ ] Firewall configured (UFW)
  - [ ] Port 22 (SSH) open
  - [ ] Port 80 (HTTP) open
  - [ ] Port 443 (HTTPS) open
  - [ ] Port 3306 (MySQL) restricted

### Phase 2: File Upload
- [ ] Create directory: `/var/www/petrogenai`
- [ ] Upload all project files
- [ ] Verify all files uploaded correctly
- [ ] Check file permissions

### Phase 3: Production Environment
- [ ] Copy `.env.example` to `.env`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_URL=https://petrogen.ai`
- [ ] Generate production key: `php artisan key:generate`

### Phase 4: Database Configuration
- [ ] Create production database
- [ ] Create dedicated database user
- [ ] Grant appropriate privileges
- [ ] Update `.env` with production credentials
- [ ] Test database connection
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Verify all tables created

### Phase 5: Dependencies Installation
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `npm install --production`
- [ ] Run `npm run build`
- [ ] Verify no errors during installation

### Phase 6: Permissions Setup
- [ ] Set owner: `chown -R www-data:www-data /var/www/petrogenai`
- [ ] Set base permissions: `chmod -R 755 /var/www/petrogenai`
- [ ] Set storage permissions: `chmod -R 775 storage`
- [ ] Set cache permissions: `chmod -R 775 bootstrap/cache`
- [ ] Create storage subdirectories
- [ ] Verify web server can write to storage

### Phase 7: Nginx Configuration
- [ ] Copy `nginx.conf` to `/etc/nginx/sites-available/petrogen.ai`
- [ ] Create symbolic link to sites-enabled
- [ ] Update server_name if needed
- [ ] Update root path if needed
- [ ] Test configuration: `nginx -t`
- [ ] Reload Nginx: `systemctl reload nginx`

### Phase 8: SSL Certificate
- [ ] Install Certbot: `apt install certbot python3-certbot-nginx`
- [ ] Run Certbot: `certbot --nginx -d petrogen.ai -d www.petrogen.ai`
- [ ] Verify SSL certificate installed
- [ ] Test HTTPS access
- [ ] Verify auto-renewal: `certbot renew --dry-run`

### Phase 9: Optimization
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Clear application cache
- [ ] Test application performance

### Phase 10: Security Hardening
- [ ] Disable directory listing
- [ ] Hide PHP version
- [ ] Configure fail2ban (optional)
- [ ] Set up regular backups
- [ ] Document all credentials securely
- [ ] Enable MySQL slow query log
- [ ] Configure log rotation

## 🧪 Production Testing Checklist

### Functionality Tests
- [ ] Visit `https://petrogen.ai`
- [ ] SSL certificate valid (green lock icon)
- [ ] Register new account works
- [ ] Login functionality works
- [ ] Chat interface loads correctly
- [ ] Send message works
- [ ] AI responds correctly
- [ ] File upload works (test PDF)
- [ ] File upload works (test image)
- [ ] File upload works (test document)
- [ ] File size limit respected (10MB)
- [ ] New conversation creation works
- [ ] Conversation history displays
- [ ] Conversation deletion works
- [ ] Logout works
- [ ] Session persistence works

### Cross-Browser Testing
- [ ] Chrome/Edge (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Mobile Chrome (Android)
- [ ] Mobile Safari (iOS)

### Responsive Design Testing
- [ ] Desktop (1920x1080)
- [ ] Laptop (1366x768)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)
- [ ] Sidebar toggle works on mobile

### Performance Testing
- [ ] Page load time < 3 seconds
- [ ] AI response time reasonable
- [ ] File upload speed acceptable
- [ ] No console errors
- [ ] No network errors
- [ ] Images/assets load correctly

### Security Testing
- [ ] HTTPS enforced
- [ ] HTTP redirects to HTTPS
- [ ] CSRF protection working
- [ ] SQL injection protected
- [ ] XSS protection working
- [ ] File upload validation working
- [ ] Unauthorized access blocked
- [ ] Password hashing verified

## 📊 Monitoring Setup Checklist

### Logging
- [ ] Laravel logs writing to `storage/logs/laravel.log`
- [ ] Nginx access logs monitored
- [ ] Nginx error logs monitored
- [ ] MySQL error logs checked
- [ ] Log rotation configured

### Backups
- [ ] Database backup script created
- [ ] Automated daily backups scheduled
- [ ] Backup storage location configured
- [ ] Backup restoration tested
- [ ] File storage backups configured

### Monitoring (Optional)
- [ ] Server monitoring tool installed (e.g., Netdata)
- [ ] Uptime monitoring configured
- [ ] Error tracking service (e.g., Sentry)
- [ ] Performance monitoring
- [ ] Disk space alerts

## 👥 User Onboarding Checklist

### Documentation
- [ ] User guide created
- [ ] FAQ document prepared
- [ ] Video tutorial recorded (optional)
- [ ] Support contact information provided

### Initial Users
- [ ] Create admin account
- [ ] Create test employee accounts
- [ ] Test with real users
- [ ] Gather feedback
- [ ] Make adjustments if needed

### Training
- [ ] Schedule training session
- [ ] Demonstrate key features
- [ ] Answer questions
- [ ] Provide documentation
- [ ] Set up support channel

## 🎯 Post-Deployment Checklist

### Week 1
- [ ] Monitor error logs daily
- [ ] Check server resources (CPU, RAM, disk)
- [ ] Verify backups running
- [ ] Collect user feedback
- [ ] Fix any critical issues

### Week 2
- [ ] Review usage statistics
- [ ] Optimize database queries if needed
- [ ] Update documentation based on feedback
- [ ] Add features if requested
- [ ] Security audit

### Month 1
- [ ] Full backup test (restore procedure)
- [ ] Performance review
- [ ] User satisfaction survey
- [ ] Plan for scaling if needed
- [ ] Update dependencies if available

## ✅ Final Sign-Off

### Development Team
- [ ] All features implemented
- [ ] All tests passed
- [ ] Documentation complete
- [ ] Code reviewed

### System Administrator
- [ ] Server configured correctly
- [ ] Security hardened
- [ ] Backups verified
- [ ] Monitoring active

### Project Manager
- [ ] All requirements met
- [ ] Budget within limits
- [ ] Timeline achieved
- [ ] Stakeholders informed

### Business Owner
- [ ] Platform tested and approved
- [ ] Users trained
- [ ] Support process in place
- [ ] Launch communication sent

## 📞 Emergency Contacts

### Technical Support
- Server Provider: _________________
- Domain Registrar: _________________
- Database Admin: _________________
- Laravel Developer: _________________

### Service Credentials (Store Securely!)
- Server Root Password: [Secure Storage]
- Database Root Password: [Secure Storage]
- OpenAI API Key: [Secure Storage]
- Domain Registrar: [Secure Storage]
- SSL Certificate: Auto-renewed by Let's Encrypt

## 🎊 Launch Announcement

- [ ] Internal announcement email sent
- [ ] Training materials distributed
- [ ] Support channel announced
- [ ] Feedback mechanism established
- [ ] Success metrics defined

---

## 📝 Notes Section

Use this space for deployment-specific notes:

**Deployment Date:** _________________

**Server IP:** _________________

**Database Name:** _________________

**Special Configurations:** 
_________________________________
_________________________________
_________________________________

**Known Issues:**
_________________________________
_________________________________
_________________________________

**Future Enhancements:**
_________________________________
_________________________________
_________________________________

---

**Deployment Status:** 
- [ ] Not Started
- [ ] In Progress  
- [ ] Complete ✅

**Sign-off Date:** _________________

**Deployed By:** _________________
