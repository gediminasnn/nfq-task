# nfq-task
Customer service department system task made for a job interview.

Demo URL : _currently not available_

## Setup

Considering you already downloaded the code you must follow these steps:


**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

You may alternatively need to run `php composer.phar install`, depending
on how you installed Composer.

**Configure the .env (or .env.local) File**

Open the `.env` file and make any adjustments you need - specifically
`DATABASE_URL`. Or, if you want, you can create a `.env.local` file
and *override* any configuration you need there (instead of changing
`.env` directly).

**Setup the Database**

Again, make sure `.env` is setup for your computer. Then, create
the database & tables!

```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

If you get an error that the database exists, that should
be ok. But if you have problems, completely drop the
database (`doctrine:database:drop --force`) and try again.

**Load the fixtures (optional)**

After fully creating both database and migrating migrations you 
can now safely store pre-made(dummy) data into the database using fixtures.

To load fixtures, just run:
```
php bin/console doctrine:fixtures:load
```

**Start the built-in web server**

You can use Nginx or Apache, but Symfony's local web server
works even better.

To install the Symfony local web server, follow
"Downloading the Symfony client" instructions found
here: https://symfony.com/download - you only need to do this
once on your system.

Then, to start the web server, open a terminal, move into the
project, and run:

```
symfony serve (-d)
```


(If this is your first time using this command, you may see an
error that you need to run `symfony server:ca:install` first).

Now check out the site at `https://localhost:8000`


Have fun!

# Project demo
###Customer's home screen
![Customer's home screen](https://user-images.githubusercontent.com/70708109/107275637-b323c980-6a5a-11eb-8c54-3de35a823bc7.png)
###Customer's appointment screen
![Customer's appointment screen](https://user-images.githubusercontent.com/70708109/107275716-cafb4d80-6a5a-11eb-9f82-a3a7bdf392d1.png)
###Specialist's Customer Management screen
![Specialists Customer Management screen](https://user-images.githubusercontent.com/70708109/107275383-52948c80-6a5a-11eb-86d0-70638c0b8dac.png)
###Service deparatment's screen
![Service department screen](https://user-images.githubusercontent.com/70708109/107275364-4f010580-6a5a-11eb-9cb9-8403c0d93d34.png)
