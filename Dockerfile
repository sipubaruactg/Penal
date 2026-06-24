FROM php:8.2-apache

# MySQL Extension Install
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Apache Rewrite Enable
RUN a2enmod rewrite

# Apache-র ডিফল্ট পোর্ট পরিবর্তন করে Render-এর $PORT ব্যবহার করার ব্যবস্থা
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf

# Website Files Copy
COPY . /var/www/html/

# Permission
RUN chown -R www-data:www-data /var/www/html

# Render-এর ডাইনামিক পোর্টের জন্য EXPOSE পরিবর্তন (ঐচ্ছিক, তবে ভালো অনুশীলন)
EXPOSE 80

CMD ["apache2-foreground"]