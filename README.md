To run this project you need Docker and Docker Compose installed. Also to execute shorter commands you'll need to use `make`. List of example commands you can find in Makefile (don't forget to change mode to dev in Makefile for development purposes!).

1. Git clone this project.
2. Clone .env.example to .env and fill it with your data. Then build containers.
   Run ```make init``` or make it manually: 
   - ```cp .env.example .env```
   - ```docker compose build --no-cache```
3. Run ```make up``` to run all containers or ```docker compose up -d --build```
4. Run init commands for laravel ```make laravel-init``` or execute commands manually one by one:
   - ```cp ./application/.env.example ./application/.env```
   - ```docker compose run --rm composer install```
   - ```docker compose run --rm artisan key:generate```
   - ```docker compose run --rm artisan migrate```
5. Laravel app is available on localhost:8888 (<-- default port, if you changed it in .env, then use the one you changed it for)
6. After a fresh build use only ```make up``` and ```make down``` to run and stop the project


- To execute composer commands run ```make composer cmd="your command"``` or ```docker compose --rm composer your_command```
- To execute artisan commands run ```make artisan cmd="your command"``` or ```docker compose --rm artisan your_command```

Frontend part:

1. Go to the application/ directory and run ```npm ci```
2. To make Vue app available start web server with ```npm run dev```
3. Now Laravel application will be able to resolve Vue app and let you load requested pages. 