# Accurate Bot
A Messenger chatbot that can assist Accurate users to find information.


## Development
1. *Install [Docker](https://docs.docker.com/get-docker/) in your local machine
2. *Git clone this project
3. *Go to project folder and `cp .env.example .env`
4. *[Install Composer dependencies](https://laravel.com/docs/8.x/sail#installing-composer-dependencies-for-existing-projects)
5. *`alias sail='bash vendor/bin/sail'`
6. `sail up -d`
7. *`sail artisan key:generate`
8. *`sail artisan storage:link`
9. App is live at http://localhost/  
\* = Only needed the first time


## Code Style
**PHP**  
`sail composer lint` and `sail composer lint:fix`


## Test
**PHP**  
`sail composer test` or `sail composer test:u` (will update snapshots)


## Share Publicly
To test the webhook with Messenger, run this command and a public URL will be generated:
`sail share`
