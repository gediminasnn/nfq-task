# nfq-task
Customer service department system task made for a job interview.
 
Demo URL : https://nfqservicedepartment.herokuapp.com/ 

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

# How to use

### Customer's home screen
Link - https://nfqservicedepartment.herokuapp.com/ 

This is main customer's homepage, customer can click on the green button to make new reservation with specialist:
![Customer's home screen](https://user-images.githubusercontent.com/70708109/107633524-d6c05d00-6c70-11eb-9f03-c8c2cbcbd956.png)
### Customer's appointment screen
Link - https://nfqservicedepartment.herokuapp.com/reservations/new/

When customer clicks homepage green button, he will be redirected to new appointment reservation page that shows all details about 
upcoming reservation. Customer can cancel the visit at any time in this page by clicking red button: 
![Customer's appointment screen](https://user-images.githubusercontent.com/70708109/107635072-3586d600-6c73-11eb-863b-01e2771d4a7a.png)
### Specialist's Customer Management screen
Link - https://nfqservicedepartment.herokuapp.com/customers/management/

This is main specialist's homepage, specialist can manage his reservations here.
![Specialists Customer Management screen](https://user-images.githubusercontent.com/70708109/107633528-d6c05d00-6c70-11eb-886f-5c8d78c6ed46.png)
### Service deparatment's screen
Link - https://nfqservicedepartment.herokuapp.com/servicedeparatment/

This is service department screen, this page's purpose is only to display current upcoming specialist's reservations:
![Service department screen](https://user-images.githubusercontent.com/70708109/107633522-d627c680-6c70-11eb-82f0-4edb5098238a.png)
