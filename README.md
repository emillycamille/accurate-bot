# Accurate Bot
A Messenger chatbot that assists Accurate users to find information.


## Development
1. *Install [Docker](https://docs.docker.com/get-docker/) in your local machine
2. *Git clone this project
2. *Go to project folder and `cp .env.example .env`
3. *[Install Composer dependencies](https://laravel.com/docs/8.x/sail#installing-composer-dependencies-for-existing-projects)
4. *`alias sail='bash vendor/bin/sail'`
5. `sail up -d`
6. *`sail artisan key:generate`
9. *`sail artisan storage:link`
10. App is live at http://localhost/  
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
