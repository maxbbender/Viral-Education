#!/bin/bash

# Update repository
sudo apt-add-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get -y upgrade

# Install Apache
sudo apt-get -y install apache2

# Allow ports in for Apache
sudo ufw allow in "Apache Full"

# Install MySQL server
debconf-set-selections <<< 'mysql-server mysql-server/root_password password domislove'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password domislove'
sudo apt-get update
sudo apt-get install -y mysql-server

# Installing PHP and it's dependencies
sudo apt-get -y install php5 libapache2-mod-php5 php5-mcrypt

# Set up apache2 root directory to be our vagrant folder
sudo rm /etc/apache2/apache2.conf
sudo cp /vagrant/.vagrant/apache2.conf /etc/apache2/apache2.conf
sudo chmod -x /etc/apache2/apache2.conf

# set up sites-enabled
sudo rm /etc/apache2/sites-enabled/000-default.conf
sudo cp /vagrant/.vagrant/000-default.conf /etc/apache2/sites-enabled/000-default.conf
sudo chmod -x /etc/apache2/sites-enabled/000-default.conf

# restart apache2
sudo service apache2 

# init database
mysql -u root -pdomislove < /vagrant/20141111_000001.db
