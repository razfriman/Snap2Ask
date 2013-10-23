# Snap-2-Ask 
Team 2 CSE 3330/3345 FALL 2013

View the live website:
### http://www.snap2ask.com


## Our Team

Raz Friman  
Raymond Martin  
Elena Villamil  
Roman Stolyarov  
Vipul Kohli  

# API Installation

## Installing Composer
  
  In order for the REST API to function properly, Composer must be installed. This is required 
  in order to install all of the included Libraries (Amazon AWS PHP SDK, Slim PHP SDK).

  1. Execute the following commands to download Composer and move it into your bin directory.
    
    ```
    $ curl -sS https://getcomposer.org/installer | php
    ```
  
    ```
    $ sudo mv composer.phar /usr/local/bin/composer
    ```
  
  2. Make sure you are in the Code/web/api directory. Then execute the following command to
  install all of the required composer dependencies.
  
    ```
    $ composer install
    ```
      
  
## Installing PHP Curl Extension

  The PHP Curl Extension must be installed in order for the REST API to function properly. This is required to call REST API functions from within other PHP files.
  
  1. Execute the following commands to install the PHP Curl Extension
  
    ```
    $ sudo apt-get install php5-curl
    ```

  2. Restart the Apache server to activate the extension
  
    ```
    $ sudo service apache2 restart
    ```
