# Compare Repository Statistics APP - draft

#### About
This repository is part of bigger application structure, 
as it is developer task, I did not create bigger architecture without 
knowing complexity of full application, this should be confronted with team.

#### Requirements
- Docker https://docs.docker.com/
- Composer https://getcomposer.org/
- Installed PHP 7.3 on your machine (to perform tests)

#### How to start:
1. in terminal: ```$ composer install```
2. You can change basic port mapping in ```docker-composer.yml```
3. ```$ docker-composer up --build```
4. That's all site is available under ```http://localhost:8081```
5. You can perform tests by ```$ php bin/phpunit tests```
##### Note:
API Documentation and is available under : ```http://localhost:8081/documentation```

---------