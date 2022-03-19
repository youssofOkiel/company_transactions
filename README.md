

## comapny transactions

set of APIs designed to manage transactions of a company.Managing transactions include: creating transactions, viewing transactions, recording
payments and generating reports with Authentication for Admin and customer.

### technologies
- PHP 7.4.10 
- composer
- Laravel 9x
- mysql db
- postman

### Packages
- passport package for authentication (it's great if we need to Oauth2 to integrate with third-party apps)
### How to install
- install requirements 
- php artisan passport:install
#### run following in order
1. php artisan migrate --path=/database/migrations/2022_03_17_092458_create_roles_table.php
2. php artisan migrate
3. php artisan db:seed --class=RoleSeeder
4. php artisan db:seed --class=UserSeeder

## Table of content
1. project structure
2. end-points
3. test cases
4. conclusion

## Project structure
#### database tables
- users
- roles
- categories
- transactions
- payments

#### controllers directories
- auth (refister, login, logout)
- admin 
- customer

#### middlewares
- isAdmin

## End-points 
###Auth
- [register] [post] (http://domain.com/api/register)
- [login] [post] (http://domain.com/api/login) 
- [logout] [get] (http://domain.com/api/logout)

### Admin
#### categories
- [create Category] [post] (http://domain.com/api/admin/createCategory)
- [view all Categories] [get] (http://domain.com/api/admin/categories) 
- [view all subCategories] [get] (http://domain.com/api/admin/subCategories/{parent_id}) 
#### transactions
- [create Transaction] [post] (http://domain.com/api/admin/createTransaction)
- [view all Transactions] [get] (http://domain.com/api/admin/transactions)
#### payments
- [record Payment] [post] (http://domain.com/api/admin/recordPayment)
- [view all Payment for specific transaction] [get] (http://domain.com/api/admin/transactionPayment/{transaction_id})

### Customer
#### transactions
- [view my transaction] [get] (http://domain.com/api/customer/transactions)

## Test 
- using two ways:
  - postman (screenShots)
  - phpunit test (run > [ php artisan test ] )
  
