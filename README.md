Snap-2-Ask
================================

Team 2

Our Team
-------------------------

Raz Friman  
Raymond Martin  
Elena Villamil  
Roman Stolyarov  
Vipul Kohli  

API Installation
-------------------------

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
  