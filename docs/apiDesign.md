## Authentication

|Method and path|Purpose|
|---|---|
|`POST /auth/register`|Register user; default global role is Member.|
|`POST /auth/login`|Authenticate and issue access token.|
|`POST /auth/logout`|Revoke current token/session.|
|`GET /auth/me`|Get current user, role, and effective permissions.|
|`PATCH /auth/password`|Change current user's password.|

## Workspaces and members

|Method and path|Authorization|Purpose|
|---|---|---|
|`GET /workspaces`|`workspaces.view`|List accessible workspaces.|
|`POST /workspaces`|`workspaces.create`|Create workspace and owner membership atomically.|
|`GET /workspaces/{workspaceId}`|`workspaces.view` + access|Get workspace.|
|`PATCH /workspaces/{workspaceId}`|`workspaces.update` + owner/policy|Update workspace.|
|`DELETE /workspaces/{workspaceId}`|`workspaces.delete` + owner/policy|Soft-delete workspace.|
|`POST /workspaces/{workspaceId}/restore`|Restore policy|Restore workspace.|
|`GET /workspaces/{workspaceId}/members`|Workspace access|List members.|
|`POST /workspaces/{workspaceId}/members`|Member-management policy|Add member.|
|`PATCH /workspaces/{workspaceId}/members/{userId}`|Member-management policy|Update membership setting.|
|`DELETE /workspaces/{workspaceId}/members/{userId}`|Member-management policy|Remove member.|
## Folders and lists

|Method and path|Authorization|Purpose|
|---|---|---|
|`GET /workspaces/{workspaceId}/folders`|Workspace access|List folders; `tree=true` returns hierarchy.|
|`POST /workspaces/{workspaceId}/folders`|Folder policy|Create root/nested folder.|
|`GET /folders/{folderId}`|Workspace access|Get folder.|
|`PATCH /folders/{folderId}`|Folder policy|Rename or change parent.|
|`DELETE /folders/{folderId}`|Folder policy|Soft-delete folder.|
|`POST /folders/{folderId}/move`|Folder policy|Move within workspace.|
|`GET /workspaces/{workspaceId}/lists`|Workspace access|List lists; filter with `folder_id`.|
|`POST /workspaces/{workspaceId}/lists`|List policy|Create list.|
|`GET /lists/{listId}`|Workspace access|Get list, defaults, workflow.|
|`PATCH /lists/{listId}`|List policy|Update list/defaults.|
|`DELETE /lists/{listId}`|List policy|Soft-delete list.|
|`POST /lists/{listId}/move`|List policy|Move list within workspace.|
|`PUT /lists/{listId}/workflow`|List policy|Replace valid status transitions.|
## Tasks

| Method and path                                         | Authorization                         | Purpose                                         |
| ------------------------------------------------------- | ------------------------------------- | ----------------------------------------------- |
| `GET /workspaces/{workspaceId}/tasks`                   | `tasks.view` + workspace access       | Search, filter, sort, and list workspace tasks. |
| `GET /lists/{listId}/tasks`                             | `tasks.view` + workspace access       | List tasks in list.                             |
| `POST /lists/{listId}/tasks`                            | `tasks.create` + workspace access     | Create task.                                    |
| `GET /tasks/{taskId}`                                   | `tasks.view` + task access            | Get task.                                       |
| `PATCH /tasks/{taskId}`                                 | `tasks.update` + task policy          | Update ordinary fields.                         |
| `DELETE /tasks/{taskId}`                                | `tasks.delete` + task policy          | Soft-delete task.                               |
| `POST /tasks/{taskId}/restore`                          | Restore policy                        | Restore task.                                   |
| `POST /tasks/{taskId}/assignee`                         | `tasks.assign` + workspace access     | Set or clear assignee.                          |
| `POST /tasks/{taskId}/status`                           | `tasks.change_status` + task policy   | Change status via list workflow.                |
| `POST /tasks/{taskId}/priority`                         | `tasks.change_priority` + task policy | Change priority.                                |
| `POST /tasks/{taskId}/move`                             | `tasks.update` + source/target access | Move task in workspace.                         |
| `GET /tasks/{taskId}/relationships`                     | `tasks.view` + access                 | List relationships.                             |
| `POST /tasks/{taskId}/relationships`                    | `tasks.update` + access               | Create relationship.                            |
| `DELETE /tasks/{taskId}/relationships/{relationshipId}` | `tasks.update` + access               | Remove relationship.                            |
| `GET /tasks/{taskId}/status-history`                    | `tasks.view` + access                 | Get status history.                             |

### Task query parameters

|Parameter|Example|Meaning|
|---|---|---|
|`q`|`q=campaign`|Search title and description.|
|`list_id`, `folder_id`|`list_id=12`|Hierarchy filter.|
|`status`, `priority`|`status=in_progress,review`|One or more values.|
|`assigned_to`, `created_by`|`assigned_to=me`|User ID or `me`.|
|`due_before`, `due_after`|ISO-8601|Due-date range.|
|`overdue`|`overdue=true`|Open overdue tasks.|
|`sort`|`sort=-due_date,title`|Fields: `created_at`, `updated_at`, `due_date`, `priority`, `title`; minus means descending.|

## Comments and attachments

|Method and path|Authorization|Purpose|
|---|---|---|
|`GET /tasks/{taskId}/comments`|`comments.view` + task access|List comments.|
|`POST /tasks/{taskId}/comments`|`comments.create` + task access|Add comment/reply.|
|`PATCH /comments/{commentId}`|`comments.update` + author/policy|Edit comment.|
|`DELETE /comments/{commentId}`|`comments.delete` + author/policy|Delete comment.|
|`GET /tasks/{taskId}/attachments`|`attachments.view` + task access|List metadata.|
|`POST /tasks/{taskId}/attachments`|`attachments.upload` + task access|Upload attachment.|
|`GET /attachments/{attachmentId}`|`attachments.view` + task access|Get metadata.|
|`GET /attachments/{attachmentId}/download`|`attachments.download` + task access|Stream or issue short-lived URL.|
|`DELETE /attachments/{attachmentId}`|`attachments.delete` + uploader/policy|Delete attachment.|

## Notifications

|Method and path|Purpose|
|---|---|
|`GET /notifications`|List current-user notifications; filters: `read`, `type`.|
|`GET /notifications/{notificationId}`|Get owned notification.|
|`POST /notifications/{notificationId}/read`|Mark one read.|
|`POST /notifications/read-all`|Mark all current-user notifications read.|
|`DELETE /notifications/{notificationId}`|Delete owned notification.|
|`GET /notification-preferences`|Get preferences.|
|`PUT /notification-preferences`|Set in-app/email preferences.|

## Users, roles, and permissions

|Method and path|Authorization|Purpose|
|---|---|---|
|`GET /users`|`users.view`|List users.|
|`POST /users`|`users.create`|Admin-create user.|
|`GET /users/{userId}`|`users.view` or self policy|Get user.|
|`PATCH /users/{userId}`|`users.update` or self policy|Update user.|
|`DELETE /users/{userId}`|`users.delete`|Soft-delete user.|
|`GET /roles`|`roles.view`|List global roles.|
|`POST /roles`|Role-management policy|Create role.|
|`GET /roles/{roleId}`|`roles.view`|Get role/permissions.|
|`PATCH /roles/{roleId}`|Role-management policy|Update role.|
|`DELETE /roles/{roleId}`|Role-management policy|Delete unused role.|
|`PUT /users/{userId}/role`|Role-management policy|Assign one global role.|
|`GET /permissions`|`permissions.view`|List permissions.|
|`PUT /roles/{roleId}/permissions`|Role-management policy|Replace role permissions.|

## Logs, exports, and write flow

|Method and path|Authorization|Purpose|
|---|---|---|
|`GET /workspaces/{workspaceId}/activity`|Workspace audit-log policy|Search/filter activity.|
|`GET /workspaces/{workspaceId}/activity/export`|Workspace audit-log policy|Create CSV/JSON export job.|
|`GET /exports/{exportId}`|Export owner/authorized policy|Get job state and download link.|
