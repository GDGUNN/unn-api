<p align="center"><img  alt="UNN logo" src ="http://www.unn.edu.ng/wp-content/uploads/2015/03/UNN_Logo.jpg" />
<br><h1 align="center">The UNN API</h1></p>
 
 <p align="center">An unofficial API for the <a href="http://unnportal.unn.edu.ng">University of Nigeria student portal</a></p>

## Endpoints

API URL: `https://unn-api.herokuapp.com/v1`

### POST `/students`: obtain a students details

#### Required parameters
```
username`: The student s unnportal.unn.edu.ng username
password`: The student s unnportal.unn.edu.ng username
```

#### Response format

Successful request (note the capitalization):
````
{
  "status": "ok",
  "message": "message",
  "student": {
      "surname": "ADEBAYO",
      "first_name": "SHALVAH",
      "middle_name": "JOSHUA",
      "sex": "Male",
      "mobile": "080xxxxxxxx",
      "email": "FIRSTNAME.LASTNAME.REGNO@UNN.EDU.NG",
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
- `authentication failed: incorrect username or password`
- `missing username`
- `missing password`

## Bugs or security vulnerabilities
If you discover any bugs or security vulnerabilities, please contact me at shalvah.adebayo@gmail.com or open an issue.

## Packages
If you are building a PHP app, you could use [this package](https://github.com/shalvah/unnportal-php), which gives you your results directly. Saves you the hassle o cURL or Guzzle.

## Contribution
If you would like to help improve this API, first of all, let me say thanks! All you need to do:
- Fork the project
- Clone your fork to your local machine
- Make your changes
- Commit your changes and push
- Open a pull request
I'll attend to all PRs as soon as I can!

## If you like this...
Please star and share! Thanks!
