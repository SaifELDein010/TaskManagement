0# _Use case_ 
- Actors and Use Cases
	- write high-level about actors and each case
- Use Case Specifications
	- Take each case and write it in detail 
### standard way of write case
#### UC-???: 
```c
Use Case: 

Actor: 

Preconditions:

Main Flow:

Alternative Flows:

Exception Flows:

```

----

## _Actors and Use Cases_

### Admin
can manage system

- manage user
- manage roles

>is there direct permission user can have or permission get from role only?
>
>user get permissions from role assign to it only, there is not direct permission in effective permissions

>role per project or global? if global what if we want this user in other role?
>
>role assign to user globally, now there is need to edit user role just assign to it role



### Project Owner
can mange projects that own 

- Create project
- Manage own project 
### Project Member 
can participate in projects according to their permissions

- view own projects
- manage assigned tasks
- manage comments

## _use cases specification_ 

### manage user
- create user
- update user
- delete user
- view user by id 
- view users 

#### UC-001: create user
```c
Use Case: create user

Actor: 
* admin

Preconditions: 
* authenticate
* access permission
  
Main Flow:
1. admin create user profile
2. admin assign global role to user
3. system validate user info
4. system apply security in user info
5. system save new user
6. system create request
7. system send response 

Alternative Flows:
A1. user can register without need admin to add him
	---> system allow to make user register 
	
A2. admin does not provide a role
    ---> system assign default role to user 

Exception Flows:
E1. user email aleardy exist
	---> validate error
	
E2. access not allow
	---> unauthorized error
```

#### UC-002: update user
```c
Use Case: update user

Actor:
* admin

Preconditions: 
* authenticate
* access permission
  
Main Flow:
1. admin enter user profile
2. system validate permission 
3. system response user info
4. admin create update info of user
5. system validate user info
6. system apply security in user info
7. system save update user
8. system create request
9. system send response 

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error
	
E2. user profile found
	---> validate error 
```

#### UC-003: delete user
```c
Use Case: delete user

Actor:
* admin

Preconditions: 
* authenticate
* access permission
  
Main Flow:
1. admin enter user profile
2. system validate permission 
3. system response user info
4. admin enter delete user profile
5. system delete user profile
6. system create request
7. system send response 

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error

E2. user profile found
	---> validate error 
```

#### UC-004: view user profile
```c
Use Case: view user profile

Actor:
* admin

Preconditions: 
* authenticate
* access permission
  
Main Flow:
1. admin enter user profile
2. system validate permission 
3. system create request
4. system send response 

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error

E2. user profile found
	---> validate error 
```

#### UC-005: view users profile
```c
Use Case: view users profile

Actor:
* admin

Preconditions: 
* authenticate
* access permission
  
Main Flow:
1. admin enter view users 
2. system validate permission 
3. system create request
4. system send response 

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error
```

### manage roles
- view users roles 
- assign role to user 

#### UC-006: view users roles  
```c
Use Case: view users roles

Actor:
* admin

Preconditions: 
* authenticate
* access permission
  
Main Flow:
1. admin view users roles
2. system validate permission 
3. system create request
4. system send response "Users info with roles"

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error
```

#### UC-007: assign role to user 
```c
Use Case: assign role to user

Actor:
* admin

Preconditions: 
* authenticate
* access permission
* user profile exist 
  
Main Flow:
1. admin view user profile 
2. system validate permission 
3. system response user profile
4. admin assign role to this user "User role assign to user globally"
5. system validate role
6. system save assign role to user
7. system create request 
8. system send response  

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error

E3. user already has a role
    ---> system send error response witout assign new role 
```

### Manage projects 
- create project
- update own project
- delete own project
- view tasks with metadata in specific project
- List own projects
- list Project members
#### UC-008: create project

```c
Use Case: create project

Actor:
* project owner
* Admin

Preconditions: 
* authenticate
* access permission
  
Main Flow:
1. actor create new project
2. system validate project
3. system save new project
4. system assign actor as a creatore 
5. system create request
6. system send response 

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error
	
E2. project info is invalid
	---> validate error
```

#### UC-009: update own project

```c
Use Case: update own project

Actor:
* project owner
* Admin

Preconditions: 
* authenticate
* access permission
  
Main Flow:
1. actor view project
2. system validate permission
3. system response with project info
4. actor make update info of project
5. system validate project info
6. system save update project
7. system create request
8. system send response 

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error
	
E2. project id not found
	---> validate error
	
E3. project does not belong to project owner
	---> unauthorized error
```

#### UC-010: delete own project

```c
Use Case: delete own project

Actor:
* project owner
* Admin

Preconditions: 
* authenticate
* access permission
  
Main Flow:
1. actor view project
2. system validate permission
3. system response project info
4. actor confirm delete project
5. system delete project info
6. system create request
7. system send response 

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error
	
E2. project id not found
	---> validate error
	
E3. project does not belong to project owner
	---> unauthorized error
```

#### UC-011: view tasks with metadata in specific project

```c
Use Case: view tasks with metadata in project

Actor: 
* authorized project user

Preconditions: 
* authenticate
* access permission
  
Main Flow:
1. authorized project user view project
2. system validate permission
3. system validate project
4. system get tasks related to project
5. system get tasks metadata
6. system create request
7. system send response 

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error
	
E2. project id not found
	---> validate error
```

#### UC-012: list own projects

```c
Use Case: list own projects

Actor: 
* project owner
* Admin

Preconditions: 
* authenticate
* access permission
  
Main Flow:
1. actor view list of projects
2. system validate permission
3. system get list of projects
4. system create request
5. system send response 

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error
```

#### UC-013: list project members

```c
Use Case: list project members

Actor: 
* project owner
* Admin

Preconditions: 
* authenticate
* access permission
  
Main Flow:
1. actor view project
2. system validate permission
3. system validate project
4. system get project members
5. system create request
6. system send response 

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error
	
E2. project id not found
	---> validate error
```

### manage assigned tasks
- view assigned tasks
- update permitted tasks
#### UC-014: view assigned tasks

```c
Use Case: view assigned tasks

Actor:
* project member

Preconditions: 
* authenticate
* access permission
  
Main Flow:
1. project member view assigned tasks
2. system validate permission
3. system get tasks assigned to project member
4. system create request
5. system send response 

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error
```

#### UC-015: update permitted tasks

```c
Use Case: update permitted tasks

Actor: 
* project member
* project owner
* Admin

Preconditions: 
* authenticate
* access permission
  
Main Flow:
1. project member view task
2. system validate permission
3. system validate task access
4. system response task info
5. project member make update info of task
6. system validate task info
7. system save update task
8. system create request
9. system send response 

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error
	
E2. task not found
	---> validate error
	
E3. project member does not have permission to update task
	---> unauthorized error
```

### manage comments
- add comment
- view comment

#### UC-016: add comment

```c
Use Case: add comment

Actor: 
* project member
* project owner
* Admin

Preconditions: 
* authenticate
* access permission
* task id is exist
  
Main Flow:
1. Actors view task
2. system validate permission
3. system response task info
4. Actors create comment
5. system validate comment info
6. system save new comment
7. system create request
8. system send response 

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error
	
E2. task not found
	---> validate error
	
E3. comment info is invalid
	---> validate error
	
E4. user does not have access to the project
    ---> unauthorized error
```

#### UC-017: view comment

```c
Use Case: view comment

Actor:
* project member
* project owner
* admin

Preconditions: 
* authenticate
* access permission
* task id is exist
  
Main Flow:
1. Actors view task
2. system validate permission
3. system response task info
4. system get comments related to task
5. system create request
6. system send response 

Alternative Flows:

Exception Flows:
E1. access not allow
	---> unauthorized error
	
E2. task id not found
	---> validate error
	
E3. project member is not a member of the project
	---> unauthorized error
```