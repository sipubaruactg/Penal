# PHP এবং Apache ব্যবহার করছি
FROM php:8.1-apache

# ওয়ার্কিং ডিরেক্টরি সেট করা
WORKDIR /var/www/html

# বর্তমান ফোল্ডারের সবকিছু সার্ভারে কপি করা
COPY . .

# Apache পোর্ট ওপেন করা
EXPOSE 80