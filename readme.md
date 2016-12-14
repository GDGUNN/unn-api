# unn-api

An unoficial API for the University of Nigeria

## Endpoints

API URL: `https://unn-api.herokuapp.com/v1`

### POST `/students`: authenticate or obtain a students details

#### Required parameters
```
username`: The student s UNNportal.unn.edu.ng username
password`: The student s UNNportal.unn.edu.ng username
```

#### Response format

Successful request (note the capitalization):
````
{
  "status": "ok",
  "message": "",
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
`authentication failed: incorrect username or password`, `missing username`, `missing password`

# Sample Usage
Here's a very simple sample for beginner-level web developers
1. Create your HTML form to collect the user's username and password. Set `method="post"` and `action="unn-form-receiver.php"` or any name you want.
2. Create the file `unn-form-receiver.php`. Put the following code in it:
```
<?php

	$ch = curl_init(); //start a cURL session

	$url = "http://unn-api.herokuapp.com/v1/students"; //the API URL
    $params = array("username" => $_POST['username'], "password" => $_POST['password']); //the required parameters

	curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	$result = json_decode(curl_exec($ch), true);

/*
At this point, your `$result` will be an array. You can check if there was an error by checking `$result['status']`. If the `status` is `ok`, you can then check `$result[`student`]`, an array of the students details. For example, `$result['student']['department']`. 

Do whatever you wish with the students details eg store them in a database. 
*/

//redirect your user to another page if you wish
header("Location: /home");
```

Thank you!