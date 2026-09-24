# Roles & Permissions

## 1. Authorization Overview

The authorization system controls what users can do in the project management system and which resources they can access

The authorization model separates two responsibilities conceptually: 

1. **Global Permissions :** Determines whether a user has permission to perform a specific type of action in the system

2. **Resource Authorization :**  Determines whether the user is allowed to access or modify a specific resource based on ownership, project membership, permissions, and business rules


This separation prevents global permissions from being used as the only security mechanism

### Authorization Flow

```
User
  ↓
Global Role
  ↓
Permissions
  ↓
Resource Authorization
  ↓
Ownership / Membership / Business Rules
  ↓
Allow or Deny Access
```

---

# 2. Permission Architecture

## 2.1 Basic Permission Model

The system uses Role-Based Access Control (RBAC)

```
User
  ↓
Role
  ↓
Permissions
```

A user does not receive permissions directly

Instead:

- Every user has one globally assigned role
- Each role contains a collection of permissions
- The user's effective permissions are inherited from their assigned role
- Changing a user's role changes their effective permissions

### Example

```
User: Ahmed
Role: Project Owner
Permissions:
  - projects.create
  - projects.view
  - projects.update
  - tasks.view
  - tasks.create
  - tasks.update
```

Ahmed's permissions come entirely from the Project Owner role

---

## 2.2 Global Roles

Roles are assigned globally to users

A role is not limited to a specific project

```
User
 └── Global Role
      └── Permissions
```

For example, if a user has the `Project Owner` role, their permissions apply wherever the authorization rules allow them to perform actions

### Role Assignment Rules

- Every user must have one global role   
- A user cannot receive direct permissions outside their role
- Roles are reusable across multiple users
- Changing a user's role updates their effective permissions
- The default role for a newly registered user is `Member` if no role is explicitly assigned

---

# 3. Permission Categories

Permissions are divided into two categories

## 3.1 Global Permissions

Global permissions answer the following question:

> Does this user have permission to perform this type of action?

Examples:

- Can the user create a project?    
- Can the user manage users?
- Can the user assign roles?
- Can the user view permissions?    

Global permissions do not automatically grant access to every resource

---

## 3.2 Resource Permissions

Resource permissions answer the following question:

> Can this user perform this action on this specific resource?

Examples:

- Can this user update this project?
- Can this user view this task?
- Can this user assign this task?
- Can this user delete this attachment?

Resource permissions must be combined with resource-level authorization rules

---

# 4. Global Permissions

Global permissions control system-level capabilities

## 4.1 User Management Permissions

|Permission|Description|
|---|---|
|`users.view`|View user profiles and user listings|
|`users.create`|Create new users|
|`users.update`|Update user information|
|`users.delete`|Delete users|

---

## 4.2 Project Management Permissions

|Permission|Description|
|---|---|
|`projects.view`|View projects|
|`projects.create`|Create new projects|
|`projects.update`|Update projects|
|`projects.delete`|Delete projects|

These permissions determine whether a user can perform project management actions. Resource authorization determines which specific projects they may access

---

## 4.3 Role and Permission Management Permissions

|Permission|Description|
|---|---|
|`roles.view`|View users and their assigned roles|
|`permissions.view`|View available permissions|

Role assignment is controlled through the role management functionality

---

# 5. Resource Permissions

Resource permissions control actions on project-related resources

## 5.1 Task Permissions

|Permission|Description|
|---|---|
|`tasks.view`|View tasks|
|`tasks.create`|Create tasks|
|`tasks.update`|Update tasks|
|`tasks.delete`|Delete tasks|
|`tasks.assign`|Assign tasks to project members|
|`tasks.change_status`|Change task status|
|`tasks.change_priority`|Change task priority|

---

## 5.2 Comment Permissions

|Permission|Description|
|---|---|
|`comments.view`|View comments|
|`comments.create`|Add comments|
|`comments.update`|Update comments|
|`comments.delete`|Delete comments|

---

## 5.3 Attachment Permissions

|Permission|Description|
|---|---|
|`attachments.view`|View attachments|
|`attachments.upload`|Upload attachments|
|`attachments.download`|Download attachments|
|`attachments.delete`|Delete attachments|

---

# 6. Resource Authorization

Having a permission alone does not guarantee access to every resource

Resource authorization validates the relationship between the user and the resource

## 6.1 Authorization Conditions

Access to a resource should consider:

```
Authentication
    +
Global Permission
    +
Resource Ownership
    +
Project Membership
    +
Business Rules
```

All relevant conditions must be satisfied before access is granted

---

## 6.2 Project Authorization

Project access depends on the following rules:

|Condition|Description|
|---|---|
|Owner|The user owns the project|
|Membership|The user is a member of the project|
|Permission|The user's role contains the required permission|
|Business Rules|The requested action follows project rules|

### Example

A user may have:

```
projects.update
```

However, this does not necessarily allow them to update every project

The system must additionally verify:

```
User owns the project
OR
User has an authorized role according to business rules
```

---

## 6.3 Task Authorization

Tasks belong to exactly one project

Before allowing access to a task, the system should verify:

1. The user is authenticated
2. The user has the required task permission
3. The task exists
4. The user has access to the task's project
5. Any additional ownership or assignment rules are satisfied

### Task Access Flow

```
User requests Task
       ↓
Check Authentication
       ↓
Check Permission
       ↓
Find Task
       ↓
Find Related Project
       ↓
Check Project Membership / Ownership
       ↓
Check Business Rules
       ↓
Allow or Deny Access
```

---

# 7. Ownership and Membership Rules

## 7.1 Project Ownership

Every project must have exactly one owner

The project owner is responsible for managing their own project according to their permissions

Relevant business rule:

- Every project must have one owner
- Only authorized users may manage project members
- Project ownership must be considered when authorizing access

---

## 7.2 Project Membership

Project membership determines whether a user participates in a project

A user may be:

- Project Owner
- Project Member

Membership is stored separately from the global role

### Important Distinction

```
Global Role
    ≠
Project Membership
```

For example:

```
User: Ahmed
Global Role: Member

Project A:
  Owner

Project B:
  Member

Project C:
  Not a member
```

The global role determines available permissions

Project membership determines whether the user can access resources within a particular project

---

# 8. Role Definitions

The system defines three primary actors

## 8.1 Admin

The Admin can manage system-level resources according to their permissions

Typical responsibilities:

- Manage users
- View and manage roles
- Assign global roles to users
- Manage projects according to authorization rules

The Admin's actual capabilities should be controlled through permissions rather than relying only on the role name

---

## 8.2 Project Owner

The Project Owner is responsible for projects they own

Typical responsibilities:

- Create projects
- Manage owned projects
- View project members
- Manage tasks within authorized projects
- Participate in comments and collaboration

The Project Owner must still pass permission checks and resource authorization checks

---

## 8.3 Project Member

The Project Member participates in projects according to their assigned permissions

Typical responsibilities:

- View accessible projects
- View assigned tasks
- Update permitted tasks
- Add comments
- View comments
- Manage attachments according to permissions

Project membership alone does not grant unlimited access

---

# 9. Permission Matrix

The following matrix defines the default authorization responsibilities of the system actors

Legend:

- **✓** = Allowed according to role and authorization rules
- **Owner** = Allowed only for resources owned by the user
- **Member** = Allowed only for resources within projects where the user is a member
- **Permission** = Requires the corresponding permission and resource authorization
- **—** = Not available by default

## 9.1 User and Role Management Matrix

|Action|Admin|Project Owner|Project Member|
|---|---|---|---|
|View users|✓|—|—|
|Create users|✓|—|—|
|Update users|✓|—|—|
|Delete users|✓|—|—|
|View user roles|✓|—|—|
|Assign global role|✓|—|—|
|View permissions|Permission|—|—|

---

## 9.2 Project Management Matrix

|Action|Admin|Project Owner|Project Member|
|---|---|---|---|
|View projects|Permission + Authorization|Own projects / authorized projects|Member projects|
|Create project|Permission|Permission|Permission|
|Update project|Permission + Authorization|Own project|According to permission and rules|
|Delete project|Permission + Authorization|Own project|According to permission and rules|
|View project members|Permission + Authorization|Own project|According to permission and rules|
|Manage project members|Permission + Authorization|Own project|According to permission and rules|

---

## 9.3 Task Management Matrix

|Action|Admin|Project Owner|Project Member|
|---|---|---|---|
|View tasks|Permission + Authorization|Authorized project tasks|Member project tasks|
|Create tasks|Permission + Project Access|Authorized project|According to permission|
|Update tasks|Permission + Resource Authorization|Authorized tasks|Assigned/permitted tasks|
|Delete tasks|Permission + Resource Authorization|Authorized tasks|According to permission|
|Assign tasks|Permission + Project Access|Authorized project|According to permission|
|Change task status|Permission + Task Authorization|Authorized tasks|Assigned/permitted tasks|
|Change task priority|Permission + Task Authorization|Authorized tasks|According to permission|

---

## 9.4 Comments Matrix

|Action|Admin|Project Owner|Project Member|
|---|---|---|---|
|View comments|Permission + Project Access|Authorized project|Project member|
|Add comments|Permission + Project Access|Authorized project|Project member|
|Update comments|Permission + Ownership/Rules|According to rules|According to rules|
|Delete comments|Permission + Ownership/Rules|According to rules|According to rules|

---

## 9.5 Attachments Matrix

|Action|Admin|Project Owner|Project Member|
|---|---|---|---|
|View attachments|Permission + Project Access|Authorized project|Project member|
|Upload attachments|Permission + Project Access|Authorized project|According to permission|
|Download attachments|Permission + Project Access|Authorized project|According to permission|
|Delete attachments|Permission + Ownership/Rules|According to rules|According to rules|

---

# 10. Permission Matrix by Permission Name

This matrix maps each permission to its general responsibility

|Permission|Resource|Purpose|
|---|---|---|
|`users.view`|Users|View users|
|`users.create`|Users|Create users|
|`users.update`|Users|Update users|
|`users.delete`|Users|Delete users|
|`projects.view`|Projects|View projects|
|`projects.create`|Projects|Create projects|
|`projects.update`|Projects|Update projects|
|`projects.delete`|Projects|Delete projects|
|`roles.view`|Roles|View roles assigned to users|
|`permissions.view`|Permissions|View available permissions|
|`tasks.view`|Tasks|View tasks|
|`tasks.create`|Tasks|Create tasks|
|`tasks.update`|Tasks|Update tasks|
|`tasks.delete`|Tasks|Delete tasks|
|`tasks.assign`|Tasks|Assign tasks|
|`tasks.change_status`|Tasks|Change task status|
|`tasks.change_priority`|Tasks|Change task priority|
|`comments.view`|Comments|View comments|
|`comments.create`|Comments|Create comments|
|`comments.update`|Comments|Update comments|
|`comments.delete`|Comments|Delete comments|
|`attachments.view`|Attachments|View attachments|
|`attachments.upload`|Attachments|Upload attachments|
|`attachments.download`|Attachments|Download attachments|
|`attachments.delete`|Attachments|Delete attachments|

---

# 11. Authorization Decision Process

Every protected request should follow a consistent authorization process

```
1. Authenticate User
       ↓
2. Retrieve User Global Role
       ↓
3. Retrieve Role Permissions
       ↓
4. Check Required Permission
       ↓
5. Identify Requested Resource
       ↓
6. Check Resource Ownership / Membership
       ↓
7. Validate Business Rules
       ↓
8. Allow or Deny Request
```

### Example: Updating a Task

```
User requests PUT /api/tasks/{task}
       ↓
Is user authenticated?
       ↓ Yes
Does user's role have tasks.update?
       ↓ Yes
Does task exist?
       ↓ Yes
Does user have access to the project?
       ↓ Yes
Is the user allowed to update this task?
       ↓ Yes
Validate task data
       ↓
Update task
```

If any authorization check fails, the system returns an authorization error

---

# 12. Database Relationship

The database supports the permission architecture through the following relationships:

```
users
  |
  | role_id
  ↓
roles
  |
  | many-to-many
  ↓
role_permissions
  ↓
permissions
```

Resource authorization is supported through ownership and membership relationships:

```
users
  ├── owns projects
  │       ↓
  │    projects
  │       ↓
  │    project_members
  │       ↓
  │    users
  │
  └── creates / assigns tasks
          ↓
        tasks
```

The database design defines:

- `users.role_id` for global role assignment
- `roles` for reusable global roles
- `permissions` for available system capabilities
- `role_permissions` for the many-to-many relationship between roles and permissions
- `projects.owner_id` for project ownership
- `project_members` for project membership
- `tasks.created_by` and `tasks.assigned_to` for task relationships

These relationships provide the foundation for separating global permission checks from resource authorization

---

# 13. Authorization Rules Summary

1. Users receive permissions through their assigned global role
2. Users do not receive direct permissions
3. Each user has one global role.
4. Roles are reusable across users.
5. Global permissions determine whether an action is generally available.
6. Resource authorization determines whether the user can access a specific resource.
7. Project ownership must be checked for owner-specific operations.
8. Project membership must be checked before accessing project resources.
9. Task access must consider the related project.
10. Task assignment must follow project membership rules.
11. Users must not access attachments by changing resource identifiers.
12. Ownership, membership, permissions, and business rules must all be considered when authorizing access.
13. Unauthorized requests must be rejected.
14. Authorization logic should be implemented independently from business logic where possible.
15. The system should use policies or authorization services to centralize resource access decisions.

---

# 14. Recommended Authorization Formula

The final authorization decision can be represented as:

```
Authorization =
    Authentication
    AND Global Permission
    AND Resource Access
    AND Business Rules
```

Where:

```
Global Permission =
    User Role contains Required Permission
```

And:

```
Resource Access =
    Ownership OR Membership OR Authorized Relationship
```

This architecture ensures that having a permission does not automatically expose all system resources to a user.