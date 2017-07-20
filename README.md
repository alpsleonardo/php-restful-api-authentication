CIRA TEST API
=================

This API is developed for the interview test at CIRA

Table of contents
=================

**[Things to know](#things-to-know)**
  * [Host](#host)
  * [Headers](#headers)

**[Authentication](#authentication)**
  * [Login](#login)
  * [Refresh](#refresh)
 
**[Users](#users)**
  * [Get User Info](#get-user-info)
  * [Create User](#create-user)
  * [Update Password](#update-password)
  * [Delete User](#delete-user)

**[Domains](#domains)**
  * [Get Domain Info](#get-domain-info)
  * [Create Domain](#create-domain)
  * [Renew Domain](#renew-domain)
  * [Delete Domain](#delete-domain)


Things to know:
---------------

### Host:
All API requests should be made to: https://{your-domain}/v1/

### Headers :
Authentication Token as well as Client ID must be passed through Header. All methods will require this process except the login method.\

client_id: "Your-secret-key" (common to all calls)
authorization: JSON Web Token (issued after the first login - either access token or refresh token)

## 1. Authentication
-----------------------------

### 1.1 Login :  
### POST /auth.php?method=login
email: (string)\
password: (string)

Returns JSON Web Tokens: access token and refresh token

#### Example JSON response
```javascript
{
    "error": false,
    "resp": {
        "access_expiry": 1500568453,
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1MDA1NjEyNDMsImp0aSI6IkgxWloxb0ZxT1ZIVEtiRkhVbUtcL2VLbHVIcGRXenplSnFnQnM1M3JJR0lNPSIsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvcGhwLWNpcmEtdGVzdC1hcGlcLyIsIm5iZiI6MTUwMDU2MTI1MywiZXhwIjoxNTAwNTY4NDUzLCJkYXRhIjp7ImlkIjoiMSIsImZpcnN0bmFtZSI6Ik1pbiIsImxhc3RuYW1lIjoiS2ltIn19.OjiKM4yb3UpnmrDvii4IjdWGp_pe1tCPB_8KplWY6AhlihZpZxPGQiyJAJnHSXuIuZqVxCf1U003ebYxzxdVag",
        "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1MDA1NjEyNDMsImp0aSI6ImRhYXh2MkkrcmhObmpqRUNpdllKUjBTMDV6d1g5a3JOZDNRQXhva1dtQ2M9IiwiaXNzIjoiaHR0cDpcL1wvbG9jYWxob3N0XC9waHAtY2lyYS10ZXN0LWFwaVwvIiwibmJmIjoxNTAwNTYxMjUzLCJleHAiOjE1MDA1OTg0NTMsImRhdGEiOnsiaWQiOiIxIiwiZmlyc3RuYW1lIjoiTWluIiwibGFzdG5hbWUiOiJLaW0ifX0.FhtTpOEpKDfgVfHsWlYW3ehEYvyrOP4EwKwTE9_PqP1OOgjz7bVEjuGAkva8Q8TU1bHrCEu8ETZyiYZP3tRrJw"
    }
}
```


### 1.2 Refresh
### POST /auth.php?method=refresh
refresh_token: (string)

Returns reissued tokens with new expiry timestamp

#### Example JSON response
```javascript
{
    "error": false,
    "resp": {
        "access_expiry": 1500568453,
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1MDA1NjEyNDMsImp0aSI6IkgxWloxb0ZxT1ZIVEtiRkhVbUtcL2VLbHVIcGRXenplSnFnQnM1M3JJR0lNPSIsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvcGhwLWNpcmEtdGVzdC1hcGlcLyIsIm5iZiI6MTUwMDU2MTI1MywiZXhwIjoxNTAwNTY4NDUzLCJkYXRhIjp7ImlkIjoiMSIsImZpcnN0bmFtZSI6Ik1pbiIsImxhc3RuYW1lIjoiS2ltIn19.OjiKM4yb3UpnmrDvii4IjdWGp_pe1tCPB_8KplWY6AhlihZpZxPGQiyJAJnHSXuIuZqVxCf1U003ebYxzxdVag",
        "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE1MDA1NjEyNDMsImp0aSI6ImRhYXh2MkkrcmhObmpqRUNpdllKUjBTMDV6d1g5a3JOZDNRQXhva1dtQ2M9IiwiaXNzIjoiaHR0cDpcL1wvbG9jYWxob3N0XC9waHAtY2lyYS10ZXN0LWFwaVwvIiwibmJmIjoxNTAwNTYxMjUzLCJleHAiOjE1MDA1OTg0NTMsImRhdGEiOnsiaWQiOiIxIiwiZmlyc3RuYW1lIjoiTWluIiwibGFzdG5hbWUiOiJLaW0ifX0.FhtTpOEpKDfgVfHsWlYW3ehEYvyrOP4EwKwTE9_PqP1OOgjz7bVEjuGAkva8Q8TU1bHrCEu8ETZyiYZP3tRrJw"
    }
}
```



