# Migrate from PILOS v1 to PILOS v2

## LDAP

LDAP is disabled by default. To enable LDAP change the `LDAP_ENABLED` option in the .env file to `true`.

### Role Mapping Syntax
The syntax for role mapping is now notated in JSON, to allow more complex role names.

**Before**

`LDAP_ROLE_MAP='A=admin,U=user'`

**After**

`LDAP_ROLE_MAP='{"A":"admin","U":"user"}'`

## Theme
In v2 the default theme and color are not based on the [corporate design](https://www.thm.de/thmweb/) guidelines of [Technische Hochschule Mittelhessen University of Applied Sciences](https://thm.de) anymore.

### Custom theme
1. Copy the content of `resources/sass/theme/default` to `resources/sass/theme/custom`
2. Adjust values in _variables.scss.
3. Change the `MIX_THEME` option in the .env file to `custom`.
4. Recompile the frontend with: `npm run production`

### v1 theme
1. Change the `MIX_THEME` option in the .env file to `thm`.
2. Recompile the frontend with: `npm run production`
