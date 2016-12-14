# unn-api

An unoficial API for the University of Nigeria

## Endpoints

API URL: `https://unn-api.herokuapp.com/v1`

### POST `/students`: authenticate or obtain a students details

#### Required parameters
```'
 `username`: The student s UNNportal.unn.edu.ng username
 `password`: The student s UNNportal.unn.edu.ng username

#### Response format

Successful request:
````
{
"status": "ok",
"message": "",
"student": {
    "surname": "Adebayo",
    "first_name": "Shalvah",
    "middle_name": "Joshua",
    "sex": "Male",
    "mobile": "080xxxxxxxx",
    "email": "FIRSTNAME.LASTNAME.REGNO@UNN>EDU>NG",
    "department": "ELECTRONIC ENGINEERING",
    "level": "200 LEVEL",
    "entry_year": "2010-2011",
    "grad_year": "2019-2020",
    "matric_no": "201x/xxxxxx",
    "jamb_no": "xxxxxxxxxx"
}
}
```

Unsuccessful request:
```
{
"status": "ok",
"message": "error_message"
}
```

Possible values of `error_message`:
`authentication failed: incorrect username or password`, `missing username`, `missing password`