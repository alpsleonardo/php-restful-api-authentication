RESTful API With JSON Web Token
=================

This API was developed in OOP PHP as an example of RESTful API with authentication layer using JSON Web Token

Table of contents
=================

**[Things to know](#things-to-know)**
  * [Host](#host)
  * [Headers](#headers)

**[Authentication](#1-authentication)**
  * [Login](#11-login)
  * [Refresh](#12-refresh)
 
**[Users](#2-users)**
  * [Get User Info](#21-get-user-info)
  * [Create User](#22-update-password)
  * [Update Password](#23-create-user)
  * [Delete User](#24-delete-user)

**[Domains](#3-domains)**
  * [Get Domain Info](#31-get-domain-info)
  * [Create Domain](#32-renew-domain)
  * [Renew Domain](#33-create-domain)
  * [Delete Domain](#34-delete-domain)


Things to know:
---------------

### Host:
All API requests should be made to: https://{your-domain}

### Headers:
Authentication Token as well as Client ID must be passed through Header. All methods will require this process except the login method.

client_id: "Your-Secret-Key" (common to all calls) 
authorization: JSON-Web-Token (issued after the first login - either access token or refresh token)

## 1. Authentication
-----------------------------

### 1.1 Login:  
### POST /v1/auth.php?method=login
- email: (string)
- password: (string)

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


### 1.2 Refresh:
### POST /v1/auth.php?method=refresh
- refresh_token: (string)

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

## 2. Users
-----------------------------

### 2.1 Get User Info:  
### GET /v1/user.php?method=getUserInfo

Returns a user info object (currently including all the columns - but this can be fixed)

#### Example JSON response
```javascript
{
    "error": false,
    "resp": {
        "id": "1",
        "firstname": "Min",
        "lastname": "Kim",
        "dob": "1992-02-21 00:00:00",
        "email": "minkim.job@gmail.com",
        "password": "minkimpassword",
        "created_at": "2017-07-15 20:05:00",
        "last_logged": "2017-07-20 13:12:45"
    }
}
```


### 2.2 Update Password:
### PUT /v1/user.php?method=updatePassword
- new_password: (string)

Returns a user info object that is updated (currently, password column is included for demonstration purpose)

#### Example JSON response
```javascript
{
    "error": false,
    "resp": {
        "id": "1",
        "firstname": "Min",
        "lastname": "Kim",
        "dob": "1992-02-21 00:00:00",
        "email": "minkim.job@gmail.com",
        "password": "minkim1234",
        "created_at": "2017-07-15 20:05:00",
        "last_logged": "2017-07-20 13:12:45"
    }
}
```

### 2.3 Create User:
### POST /v1/user.php?method=createUser
- firstname: (string)
- lastname: (string)
- dob: (string)
- email: (string)
- password: (string)

Returns a user info object that is created (currently, password column is included for demonstration purpose)

#### Example JSON response
```javascript
{
    "error": false,
    "resp": {
        "id": "18",
        "firstname": "Michael",
        "lastname": "Jackson",
        "dob": "1958-08-29 00:00:00",
        "email": "michael.jackson@dead.com",
        "password": "michaeljacksonstillalive",
        "created_at": "2017-07-20 13:18:04",
        "last_logged": "0000-00-00 00:00:00"
    }
}
```
### 2.4 Delete User:
### DELETE /v1/user.php?method=deleteUser
- email: (string)
- password: (string)

Returns an object that contains a message of successful delete

#### Example JSON response
```javascript
{
    "error": false,
    "resp": "Successfully deleted"
}
```

## 3. Domains
-----------------------------

### 3.1 Get Domain Info:  
### GET /v1/domain.php?method=getDomainInfo

Returns an object that contains all the domains by a user id

#### Example JSON response
```javascript
{
    "error": false,
    "resp": [
        {
            "id": "1",
            "user_id": "1",
            "domain_name": "unreal.ca",
            "created_at": "1500239400",
            "expire_at": "2036351400"
        },
        {
            "id": "2",
            "user_id": "1",
            "domain_name": "notreal.ca",
            "created_at": "1500239460",
            "expire_at": "1531775460"
        },
        {
            "id": "8",
            "user_id": "1",
            "domain_name": "mytriage.ca",
            "created_at": "1500348660",
            "expire_at": "1531884660"
        },
        {
            "id": "9",
            "user_id": "1",
            "domain_name": "newehealth.ca",
            "created_at": "1500349114",
            "expire_at": "1531885114"
        },
        {
            "id": "10",
            "user_id": "1",
            "domain_name": "richdomain.ca",
            "created_at": "1500349118",
            "expire_at": "1531885118"
        },
        {
            "id": "11",
            "user_id": "1",
            "domain_name": "australiatravel.ca",
            "created_at": "1500352353",
            "expire_at": "1531888353"
        },
        {
            "id": "12",
            "user_id": "1",
            "domain_name": "putyourshoes.ca",
            "created_at": "1500352525",
            "expire_at": "1531888525"
        }
    ]
}
```

### 3.2 Renew Domain:
### PUT /v1/domain.php?method=renewDomain
- domain_id: (int)

Returns an object that contains the domain info with newly updated expiry timestamp

#### Example JSON response
```javascript
{
    "error": false,
    "resp": {
        "id": "13",
        "user_id": "1",
        "domain_name": "algonquinexample.ca",
        "created_at": "1500646431",
        "expire_at": "1563718431"
    }
}
```
### 2.3 Create Domain:
### POST /v1/domain.php?method=createDomain
- domain_name: (string)

Returns an object that contains the new domain info

#### Example JSON response
```javascript
{
    "error": false,
    "resp": {
        "id": "13",
        "user_id": "1",
        "domain_name": "algonquinexample.ca",
        "created_at": "1500646431",
        "expire_at": "1532182431"
    }
}
```
### 3.4 Delete Domain:
### DELETE /v1/domain.php?method=deleteUser
- domain_id: (int)

Returns an object that contains a message of successful delete

#### Example JSON response
```javascript
{
    "error": false,
    "resp": "Successfully deleted"
}
```
