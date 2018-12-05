# teamLab_task
A RESTful AP that registers, searches, changes, and deletes product data with the following status. 
<br>・product's Image (maximum 100 characters)
<br>・product's Name (maximum 500 characters)
<br>・Explanatory text
<br>・Price
# Dependency
Language:php
<br>Framework:Phalcon PHP
# Setup
Database:Mysql
```
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` nchar varying(100) NOT NULL,
  `exp` nchar varying(500) NOT NULL,
  `price` int(20) NOT NULL,
  PRIMARY KEY (`id`)
);
```
**Implementation of "Product's Image" has not been done yet.**
# The Author
Takashi Yamada
# LICENSE
This software is released under the MIT License, see LICENSE.
# RELEASED
# RELEASED SCHEDULE
