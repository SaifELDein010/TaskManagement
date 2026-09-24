# Workspace Management Database Design

This design replaces the project-only model. A workspace is the top-level boundary; it contains nested folders, folders contain lists, and lists contain tasks. Global roles are separate from workspace membership.

```typescript
Table users {

  id int [pk]

  role_id int [ref: > roles.id, null]

  username varchar [not null, unique]

  email varchar [not null, unique]

  password_hash varchar [not null]

  created_at datetime

  updated_at datetime

}

  

Table roles {

  id int [pk]

  name varchar

  created_at datetime

  updated_at datetime

}

  

Table permissions {

  id int [pk]

  name varchar

  created_at datetime

  updated_at datetime

}

  

Table role_permissions{

  role_id int [ref: > roles.id, not null]

  permission_id int [ref: > permissions.id, not null]

  created_at datetime

  updated_at datetime

  

  indexes {

    (role_id, permission_id) [pk]

  }

}

  

Table workspaces {

  id int [pk]

  owner_id int [ref: > users.id, not null]

  name varchar [not null]

  description text

  created_at datetime

  updated_at datetime

}

  

Table workspace_members {

  project_id int [ref: > workspaces.id]

  member_id int [ref: > users.id]

  created_at datetime

  updated_at datetime

  

  indexes {

    (project_id, member_id) [pk]

  }

}

  

Table folders {

  id int [pk]

  workspace_id int [not null, ref: > workspaces.id]

  parent_folder_id int [ref: > folders.id]

  created_by int [not null, ref: > users.id]

  name varchar [not null]

  created_at datetime [not null]

  updated_at datetime [not null]

}

  

Table lists {

  id int [pk]

  workspace_id int [not null, ref: > workspaces.id]

  folder_id int [ref: > folders.id]

  created_by int [not null, ref: > users.id]

  name varchar [not null]

  default_assignee_id int [ref: > users.id]

  default_priority task_priority [not null, default: 'medium']

  default_due_in_days int

  created_at datetime [not null]

  updated_at datetime [not null]

}

  

Table tasks {

  id int [pk]

  list_id int [not null, ref: > lists.id]

  created_by int [ref: > users.id, not null]

  assigned_to int [ref: > users.id, null]

  title varchar

  description text

  status varchar

  priority varchar

  due_date datatime

  created_at datetime

  updated_at datetime

}

  

Table comments {

  id int [pk]

  task_id int [ref: > tasks.id]

  user_id int [ref: > users.id]

  parent_comment_id int [ref: > comments.id, null]

  body text [not null]

  created_at datetime

  updated_at datetime

}

  

Table attachments {

  id int [pk]

  task_id int [ref: > tasks.id, not null]

  uploaded_by int [ref: > users.id, not null]

  original_name varchar [not null]

  stored_name varchar [not null]

  storage_path varchar [not null]

  mime_type varchar

  size bigint

  created_at datetime

  updated_at datetime

}

  

Table notifications {

  id int [pk]

  user_id int [ref: > users.id, not null]

  type varchar [not null]

  title varchar [not null]

  message varchar [not null]

  notifiable_type varchar

  notifiable_id int

  read_at datetime [default: null]

  created_at datetime

  updated_at datetime

  

  indexes {

    (user_id, read_at)

    (user_id, created_at)

    (notifiable_type, notifiable_id)

  }

}

  

Table status_history_logs {

  id int [pk]

  task_id int [ref: > tasks.id]

  user_id int [ref: > users.id]

  status varchar [not null]  

  created_at datetime

}
```

## Enforcement notes

- Create the workspace and its owner membership (`member_role = 'owner'`) in one transaction.
- A folder parent, list folder, task status, default assignee, and task assignee must belong to the same workspace. Enforce these cross-table rules in application validation or database triggers.
- Seed each list with the lifecycle statuses Pending, In Progress, Review, Rejected, Completed, and Cancelled. `task_status_transitions` defines the valid moves.
- Prevent a task from relating to itself; use a canonical direction for symmetric relationships such as `duplicates` and `relates_to`.
- `deleted_at` implements soft delete; normal reads must filter out deleted rows.
- Validate attachment MIME types/extensions and maximum size before creating an attachment or version row.
