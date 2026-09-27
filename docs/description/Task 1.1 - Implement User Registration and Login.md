# Workflow of User Registration and Login
## Register flow

POST /auth/register
#### Flow of register in AuthController
1- validate data from register request 
2- create user record using register in AuthService
3- return response with user info (without password)

#### Flow of register in AuthService
1- check role if there is role provide then assign by default super admin role
2- get role id of role name from “roleRepository” 
3- if role not found send error response 
4- if role exist and valid then save new user 
5- return user info so controller can use it in response


---

## Login flow 

POST /auth/login
#### Flow of login in AuthController
1- validate data from login request 
2- authenticate provide data in login in AuthService to create token
3- return response with user info (without password) and token

#### Flow of login in AuthService
1- from userRepository get user data by email
2- if there is user with provide email → then send error response 
3- if user email is right → then check password hash is match 
	3.1- if password hash not match → then send error response 
4- create token
5- return user info and token so controller can use it in response

---

## Logout flow 

POST /auth/logout
#### Flow of logout in AuthController
1- using logout in authService revoke token
2- send response 

#### Flow of login in AuthService
1- sample just make token invalidate

---
---

# Some notes in each files of code
## roles table

**NOTE :** Build it first to reduce migrations cuz I using role id in user 

name → name of role
description → to explain what role doing (is a NULL filed)


---

## users table

**NOTE :** default user role I will make it in APP-level cuz default is a business rule can change any time
- Also in APP-level can using name of role to determine default instead of using Id of role (id have no meaning)

role_id → foreign key of id from roles table
email and username is unique 
password → store password as a encryption hash   


--- 

## Role model

Role has many users 

---

## RoleSeeder

create default roles with description to using it in default role when user create account without provide role 

default roles :
- Super admin
- Admin
- Project owner
- Project member 

---

## User model

using hash function to password to make it hashing 

user belong to only one role 


---

## User Repository

User Repository interface for now: 
``` 
create()
findByEmail()
```


---
---

## General Notes

- I using “tymon/jwt-auth” as a JWT package  

```
1. composer require tymon/jwt-auth
2. php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
3. php artisan jwt:secret
```