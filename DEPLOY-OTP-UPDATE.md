# Deploy OTP Authentication Update to petrogen.ai

## 🚀 Quick Deployment Steps

### 1. Pull Latest Code from GitHub

SSH into your GoDaddy server and run:

```bash
cd ~/public_html
git pull origin main
```

### 2. Run New Migration

```bash
php artisan migrate --force
```

This will add OTP fields to users table:
- `otp_code` (6-digit verification code)
- `otp_expires_at` (expiration timestamp)
- `email_verified` (verification status)

### 3. Update Environment Variables

Make sure your `.env` has these mail settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=petrogen.ai
MAIL_PORT=465
MAIL_USERNAME=no-replay@petrogen.ai
MAIL_PASSWORD=@2021996Petrogenai
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="no-replay@petrogen.ai"
MAIL_FROM_NAME="PetrogenAI"
```

### 4. Clear All Caches

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Set Permissions

```bash
chmod -R 775 storage bootstrap/cache
chown -R $USER:www-data storage bootstrap/cache
```

---

## 🔐 What's New

### OTP-Based Registration
- Only `@petrogen.sa` emails allowed
- 6-digit OTP sent to email
- 10-minute expiration
- Email verification required before login

### OTP-Based Login (2FA)
- Every login requires OTP verification
- 6-digit code sent to email
- Adds extra security layer
- OTP expires in 10 minutes

### New Routes
- `GET /verify` - Registration OTP verification page
- `POST /verify` - Process registration OTP
- `POST /verify/resend` - Resend registration OTP
- `GET /login/verify` - Login OTP verification page
- `POST /login/verify` - Process login OTP
- `POST /login/verify/resend` - Resend login OTP

### Email Configuration
- Emails sent from: `no-replay@petrogen.ai`
- SMTP server: `petrogen.ai:465` (SSL)
- Clean, professional email templates

---

## ✅ Testing After Deployment

1. **Test Registration:**
   - Go to https://petrogen.ai/register
   - Try registering with non-@petrogen.sa email (should fail)
   - Register with @petrogen.sa email
   - Check email for OTP code
   - Enter OTP to verify

2. **Test Login:**
   - Login with verified account
   - Check email for login OTP
   - Enter OTP to complete login

3. **Test OTP Resend:**
   - Try resending OTP if not received
   - Verify new OTP works

---

## 🔧 Troubleshooting

### If emails not sending:

Check mail logs:
```bash
tail -f storage/logs/laravel.log | grep -i mail
```

Test SMTP connection:
```bash
php artisan tinker
Mail::raw('Test email', function($msg) { $msg->to('your@email.com')->subject('Test'); });
```

### If OTP verification fails:

Clear caches:
```bash
php artisan cache:clear
php artisan config:clear
```

Check database:
```bash
php artisan tinker
User::where('email', 'test@petrogen.sa')->first(['otp_code', 'otp_expires_at', 'email_verified']);
```

### If routes not working:

```bash
php artisan route:clear
php artisan route:cache
```

---

## 📝 Database Changes

New columns in `users` table:
- `otp_code` - VARCHAR(6), nullable
- `otp_expires_at` - TIMESTAMP, nullable  
- `email_verified` - BOOLEAN, default false

---

## 🎯 All-in-One Deployment Command

```bash
cd ~/public_html && \
git pull origin main && \
php artisan migrate --force && \
php artisan optimize:clear && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
chmod -R 775 storage bootstrap/cache && \
echo "✅ Deployment complete! OTP authentication is now active."
```

---

## 📧 Email Template Examples

**Registration OTP:**
```
Your PetrogenAI verification code is: 123456

This code will expire in 10 minutes.

If you didn't request this code, please ignore this email.
```

**Login OTP:**
```
Your PetrogenAI login verification code is: 123456

This code will expire in 10 minutes.

If you didn't request this code, please secure your account immediately.
```

---

That's it! 🎉 Your OTP authentication system is now live on https://petrogen.ai
