# Workflow 

## View user profile

GET /auth/me

#### Flow of me in AuthController
1- from “me” in authService get profile of authenticate user
2- send response of this profile  

#### Flow of me in AuthService
1- from JWT get profile of authenticate user
2- return user info so controller can use it in response

---

## Update User Profile Password

PATCH /auth/updatePassword 

#### Flow of updatePassword in AuthController
1- validate requests using change password request 
2- send request after validating to “updatePassword” in authService
2- send response of this profile with message after password updating 

#### Flow of updatePassword in AuthService
1- from JWT get profile of authenticate user
2- compare current password hash with hash password if valid
3- updating password using updatePassword service in userRepository
4- return user info so controller can use it in response