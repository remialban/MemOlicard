# MemOlicard
Web application to help the memorization in using flashcards.

## Requirement
PHP 8.0.2 and later.

## Installation
Clone this project:
```
git clone https://github.com/remialban/MemOlicard.git
```
```
cd MemOlicard
```
Installing dependencies:
```
composer install
```
```
npm install
```

## Configuration
Edit the `.env` file and change the `DATABASE_URL` line. Choose one of this line in replacing user and password and the server ip by your informations:
```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/memolicard?serverVersion=5.7"
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/app?serverVersion=13&charset=utf8"
```
To use the Google authentification, you need to create authorization credentials in Google Cloud Platform (https://developers.google.com/identity/protocols/oauth2/web-server#creatingcred) and replace your credentials in the `.env` file:
```
OAUTH_ID_GOOGLE=<GOOGLE_ID>
OAUTH_SECRET_KEY_GOOGLE=<GOOGLE_KEY>
```
## Update database
Creation of the database:
```
php bin/console doctrine:database:create
```
Updating database:
```
php bin/console doctrine:schema:update --force
```

## Run
You have to run two shell and run this commands :
```
php -S localhost:8000 -t public
```
```
npm run dev-server
```

That's finish! You can access to MemOlicard at this url : http://localhost:8000!
