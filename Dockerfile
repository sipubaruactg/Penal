# PHP 8.1 Apache বেস ইমেজ
FROM php:8.1-apache

# সার্ভারের রুট ডিরেক্টরিতে কাজ করা
WORKDIR /var/www/html

# বর্তমান ফোল্ডারের ফাইলগুলো কনটেইনারে কপি করা
COPY . .

# Apache মডিউল এনাবল করা (URL Rewriting-এর জন্য)
RUN a2enmod rewrite

# পোর্ট ৮০ ওপেন রাখা
EXPOSE 80

# Apache অটো-স্টার্ট করার কমান্ড
CMD ["apache2-foreground"]
