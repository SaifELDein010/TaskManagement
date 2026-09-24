# Requirement DOC
* 1. Project purpose
* 2. Main users
* 3. Features
* 4. Business rules
* 5. Permissions
* 6. Task lifecycle
* 7. Notification requirements
* 8. File requirements
* 9. API requirements

-----------
---

## 1. Project purpose 

The purpose of the workspace management API is to provide a centralized system for transforming unstructured organizational work into a visible, organized, and coordinated system by managing workspace, structure folders, tasks, users, responsibilities, and work progress

- Organize folders and Tasks by allowing users to create folders, break folders into lists have tasks, assign tasks to users, and track their progress

- Organize Users and Responsibilities by managing users and defining their involvement and responsibilities within projects and tasks

- Track Work Progress through statuses, priorities, due dates, relationships between projects and tasks, and task lifecycle transitions

- Improve Collaboration by making it clear who is responsible for each task and which project each task belongs to

---

## 2. Main users  
### Admin
Have access to all action in system
### Workspace owner
Have access in workspace scope 
### Workspace member
Have access in folders scope

---

## 3. Features

### 3.1 Functional Requirements

#### Authentication

- Registration
- Login
- Logout
- View current user
- Change password
#### Notifications

notifications when assign to task, there change in assign task (status, priority), adding comment, attachment in assign task
	- notification in: 
		- database notifications
		- e-mail notifications

- View notifications
- Mark notification as read
- Delete notification
- Manage notification preferences

#### Workspace management

workspace is the top level area where users organize and manage their work

- Create workspace
- View workspace
- List workspaces
- Update workspace
- Delete workspace

#### Workspace Member Management

mange members belong to workspace

- Add member to workspace
- View workspace members
- Update member access/role
- Remove member from workspace

#### Folder Management

the area that user using to design his own structure that match their needs 

- Create folder
- View folder
- Update folder
- Delete folder
- Move folder
- Create nested folder

#### List Management

A List groups related tasks within a particular part of the hierarchy

- Create list
- View list
- Update list
- Delete list
- Move list
- Configure task workflow
- Configure default task settings
- Configure task views
- Configure task assignment defaults
- Configure task lifecycle rules
- Manage custom fields
- Manage automation rules
- Manage task templates

#### Task Management

peace of work should done in certain data time 

- Create task
- View task
- List tasks
- Update task
- Delete task
- Assign task
- Change task status
- Change task priority
- Set due date
- Set creator
- Set assignee
- Move task
- Manage task relationships

#### Comments

- Create comment
- View comment
- Update comment
- Delete comment

#### Attachments

- Upload attachment
- View attachment
- Download attachment
- Delete attachment

#### Data Retrieval

- Search data
- Filter data
- Sort data
- Paginate data

#### User Management

- Create user
- View user
- List users
- Update user
- Delete user

#### Role Management

- Create role
- View role
- List roles
- Update role
- Delete role
- Assign role to user
- Remove role from user
- Assign default role
- View user's assigned role

#### Permission Management

- View available permissions
- View role permissions
- Assign permissions to role
- Remove permissions from role

#### Logs

Types of logs :
- Status history per task 

- View logs
- Search logs
- Filter logs
- Export logs

#### System features 
- Events
  - Task created
  - Task assigned
  - Task status changed

- Background jobs
- Queues
- Failed job handling

----



### 3.2 Non-Functional Requirements
- Security
	- Password hashing
	- Protected endpoints
	- Policies and permissions
	- Ownership and membership checks
	- SQL injection prevention
	- Mass-assignment protection
	- Sensitive-data protection
	- Rate limiting
	- Secure file storage and access
- Performance
	- Avoiding N+1 queries
	- Indexing
	- Pagination for large datasets
	- Avoiding repeated queries
	- Queues for expensive operations
	- Caching where appropriate
- API consistency
- Error handling
	- Validation errors
	- Authentication errors
	- Authorization errors
	- Missing resources
	- Database errors
	- File errors
	- Business-rule errors
	- Unexpected server errors
- Maintainability
- API Resources / consistent response transformation

### 3.3 Additional features
- logs 
	- Authentication log
	- project log
- Dashboard & Statistics  
- Overdue Task Management  
- Task Tags & Labels  
- Subtasks  
- Teams & Departments  
- Task Reporting  
- Soft Delete & Restore  
- User Mentions  
- File Versioning

---

## 4. Business rules  
- Every project must have one owner
- Only authorized users may manage project members
- A task must belong to exactly one project
- A task must have a creator
- A task assignee should be a member of the relevant project
- Only authorized users may assign tasks
- A user may only update or delete resources according to their permissions and business rules
- Task status changes must follow the defined task lifecycle
- Invalid task status transitions must be rejected
- A user must not access an attachment only by changing its identifier
- Project and task access must consider ownership, membership, permissions, and business rules
- user role assign to it global not per project

---

## 5. Permissions  

### Global Permissions (user have permission to do this?)
```
users.view
users.create
users.update
users.delete

projects.view
projects.create
projects.update
projects.delete

roles.view

permissions.view
```

### Resource Permissions (user can access this resource?)
```
tasks.view
tasks.create
tasks.update
tasks.delete
tasks.assign
tasks.change_status
tasks.change_priority

comments.view
comments.create
comments.update
comments.delete

attachments.view
attachments.upload
attachments.download
attachments.delete
```


```
Global role
    ↓
Permissions
    ↓
Access to project resources
```

---

## 6. Task lifecycle 
```
Pending  →  can to be Cancelled
↓
In progress → can to be Cancelled
↓
Review → Rejected → can to be Cancelled or to be In progress again 
↓ "Approved"
Completed
```

---

## 7. Notification requirements 
### Notification for :
- Task assign
- update in assign task 
	- status change
	- comments
#### Optional (in assign task)
	- priority change
	- update metadata
	- attachment

### Notification in :
- database notifications
- e-mail notifications

---

## 8. File requirements  

Note : file is the same of attachment 
### Supported File Types

- Image
- TXT
- JSON
- PDF

### File Validation

- allowed file types
- allowed file extensions
- maximum file size
- storage path
- secure file naming

### File Authorization

- can user upload attachments?
- can user view attachments?
- can user download attachments?
- can user delete attachments?

### File Storage

including:
```
original name
stored name
path
mime_type
size
```

---

## 9. API requirements

### Authentication API  
```JSON
POST /api/auth/register
POST /api/auth/login
POST /api/auth/logout
GET  /api/auth/me
POST /api/auth/password/change
```

### Project API  
```JSON
GET    /api/projects
POST   /api/projects
GET    /api/projects/{project}
PUT    /api/projects/{project}
DELETE /api/projects/{project}
```

### Project Members API  
```JSON
GET    /api/projects/{project}/members
POST   /api/projects/{project}/members
PUT    /api/projects/{project}/members/{user}
DELETE /api/projects/{project}/members/{user}
```

### Task API  
```JSON
GET    /api/projects/{project}/tasks
POST   /api/projects/{project}/tasks

GET    /api/tasks/{task}
PUT    /api/tasks/{task}
DELETE /api/tasks/{task}

POST /api/tasks/{task}/assign
```

### Comments API  
```JSON
GET    /api/tasks/{task}/comments
POST   /api/tasks/{task}/comments

PUT    /api/comments/{comment}
DELETE /api/comments/{comment}
```

### Attachments API  
```JSON
GET    /api/tasks/{task}/attachments
POST   /api/tasks/{task}/attachments

GET    /api/attachments/{attachment}/download
DELETE /api/attachments/{attachment}
```

### Users API  
```JSON
GET    /api/users
GET    /api/users/{user}
PUT    /api/users/{user}
DELETE /api/users/{user}
```
