#!/bin/bash
cd ~/petrogenai

echo "=== Checking cPanel Username ==="
whoami

echo ""
echo "=== Possible Database Configurations ==="
echo "If your cPanel username is: f9x6j6g74lx9"
echo "Then database name could be: f9x6j6g74lx9_petrogen_ai"
echo "And username could be: f9x6j6g74lx9_toorx"

echo ""
echo "=== Testing different username formats ==="

# Test 1: With cPanel prefix
echo "Test 1: f9x6j6g74lx9_toorx / f9x6j6g74lx9_petrogen_ai"
mysql -u f9x6j6g74lx9_toorx -p'Sal@688$xz' -e "SHOW DATABASES;" 2>&1 | head -5

# Test 2: Original credentials
echo ""
echo "Test 2: toorx / petrogen_ai"
mysql -u toorx -p'Sal@688$xz' -e "SHOW DATABASES;" 2>&1 | head -5

# Test 3: Check .my.cnf
echo ""
echo "=== Checking for MySQL config ==="
if [ -f ~/.my.cnf ]; then
    echo "Found .my.cnf:"
    cat ~/.my.cnf
else
    echo "No .my.cnf found"
fi

echo ""
echo "=== Current .env database settings ==="
grep "DB_" ~/petrogenai/.env

echo ""
echo "NEXT STEPS:"
echo "1. Login to cPanel"
echo "2. Go to MySQL Databases"
echo "3. Find the exact database name and username"
echo "4. They usually have format: cpaneluser_dbname"
