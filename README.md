# PermissionEx-PM
PermissionEx-PM is easy to use plugin for manage players permissions.

ATTENTION! This plugin works only on PocketMine-MP (not Bukkit).

## General commands
Command | Permission | Description
--- | --- | ---
/pex help | `pex.command.general` | Shows all commands
/pex info | `pex.command.general` | Shows plugin information
/pex reload | `pex.command.reload`. | Reloads plugin
/pex set default group (group) | `pex.command.set` | Sets default group

## User commands
Command | Permission | Description
--- | --- | ---
/pex user | `pex.command.users` | Shows the list of registered users
/pex user (user) | `pex.command.users` | Shows the list of user groups
/pex user (user) list | `pex.command.users` | Shows the list of user permissions
/pex user (user) delete | `pex.command.users` | Deletes user data
/pex user (user) add (permission) (time[s/m/h/d])| `pex.command.users` | Adds permission to the user
/pex user (user) remove (permission) | `pex.command.users` | Removes permission from the user
/pex user (user) group set (group) | `pex.command.users` | Sets user group
/pex user (user) group add (group) (time[s/m/h/d]) | `pex.command.users` | Adds group to the user
/pex user (user) group remove (group) | `pex.command.users` | Removes group from the user

## Group commands
Command | Permission | Description
--- | --- | ---
/pex group | `pex.command.groups` | Shows the list of registered groups
/pex group (group) create (parents) | `pex.command.groups` | Creates group
/pex group (group) delete | `pex.command.groups` | Deletes group
/pex group (group) add (permission) (time[s/m/h/d]) | `pex.command.groups` | Adds permission to the group
/pex group (group) remove (permission) | `pex.command.groups` | Removes permission from the group
/pex group (group) list | `pex.command.groups` | Shows the list of group permissions
/pex group (group) players | `pex.command.groups` | Shows the list of group players
/pex group (group) parents set (parents) | `pex.command.groups` | Sets parents of the group
/pex group (group) parents add (parents) | `pex.command.groups` | Adds a parent to the group
/pex group (group) parents remove (parent) | `pex.command.groups` | Removes a parent from the group
