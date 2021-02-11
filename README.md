# nfq-task
Customer service department system task made for a job interview.

Task itself :

*Screens with serial numbers can be seen in customer service departments (bank, outpatient
clinic, post office, etc.). The incoming customer prints out the number and the display shows the
waiting line and the customers place in it. To avoid serial numbers printed on paper, a system is
needed that could allow the customer to book a visit time using a website. After booking the
time of the visit, the customer could see the waiting line and their respected place in it. The
queue is displayed on the service department screens. The customer can see how much time
he has left before the meeting according to the reservation code.*

# How to use

### Customer's home screen
* The customer must be able to reserve an appointment with a specialist (the customer
does not need to register an account in the system). The user does not select any
specific time, the system calculates the next available appointment time. After a
successful reservation, the system must generate a reservation code and provide it to
the customer.

Link - https://nfqservicedepartment.herokuapp.com/ 

This is main customer's homepage, customer can click on the green button to make new reservation with specialist:
![Customer's home screen](https://user-images.githubusercontent.com/70708109/107633524-d6c05d00-6c70-11eb-9f03-c8c2cbcbd956.png)
### Customer's appointment screen
* The customer must see how much time is left before the visit (separate page, not the
 service department screen).
* The customer must be able to cancel the visit.

Link - https://nfqservicedepartment.herokuapp.com/reservations/new/

When customer clicks homepage green button, he will be redirected to new appointment reservation page that shows all details about 
upcoming reservation. Customer can cancel the visit at any time in this page by clicking red button: 
![Customer's appointment screen](https://user-images.githubusercontent.com/70708109/107640597-2f94f300-6c7b-11eb-9a5a-1280c93a52a4.png)
### Specialist's Customer Management screen
* The specialist must have an account (can be created through a database, no
administration is required for accounts) with which to log in to visit management.
* The specialist should only see patients who have registered with him.
* The specialist must be able to mark that the visit has begun. There can only be one
active visit at a time.
* The specialist must be able to mark the end of the visit.
* The specialist must be able to cancel the visit.

Link - https://nfqservicedepartment.herokuapp.com/customers/management/

This is main specialist's homepage, specialist can manage his reservations here.
![Specialists Customer Management screen](https://user-images.githubusercontent.com/70708109/107640600-30c62000-6c7b-11eb-9b9a-06ede56b7719.png)
### Service deparatment's screen
* The service department screen should show current visits and five upcoming visits.
* The service department screen information must be updated every five seconds.
* The service department screen must not be publicly accessible.

Link - https://nfqservicedepartment.herokuapp.com/servicedeparatment/

This is service department screen, this page's purpose is only to display current visits and five upcoming specialist's visits:
![Service department screen](https://user-images.githubusercontent.com/70708109/107640598-302d8980-6c7b-11eb-99b6-88ab49a78873.png)
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

*Note : you must be in dev environment to run fixures, if you are in prod you must create all information manually.*

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
