FROM mcr.microsoft.com/devcontainers/php:4-8.4-bookworm

# Install MariaDB client
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y mariadb-client \
    && apt-get clean -y && rm -rf /var/lib/apt/lists/*

# Install PHP MySQL driver
RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY php.ini /usr/local/etc/php/conf.d/99-upload.ini

# Install Node.js
RUN su vscode -c "source /usr/local/share/nvm/nvm.sh && nvm install 22 && nvm alias default 22"