#!/bin/bash

# 1. Install & Build Frontend (Vite/Node)
echo "Installing Frontend Dependencies..."
npm install
echo "Building Frontend Assets..."
npm run build

# 2. Siapkan PHP & Composer untuk Backend
echo "Setting up PHP environment..."

# Download PHP Static Binary (agar tidak perlu yum/apt)
# Menggunakan static-php-cli atau resource terpercaya
mkdir -p bin
cd bin
if [ ! -f php ]; then
    echo "Downloading Static PHP..."
    curl -o php https://raw.githubusercontent.com/vercel-community/php/master/bin/php8.2
    chmod +x php
fi
cd ..

# Tambahkan bin ke PATH sementara
export PATH=$PWD/bin:$PATH

# Cek PHP
php -v

# 3. Install Composer Dependencies
echo "Installing Composer Dependencies..."
if [ ! -f composer.phar ]; then
    curl -sS https://getcomposer.org/installer | php
fi

php composer.phar install --no-dev --optimize-autoloader --ignore-platform-reqs

echo "Build Complete!"
