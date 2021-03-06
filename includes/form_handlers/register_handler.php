<?php

	// DECLARING VARIABLES TO PREVENT ERRORS
	$first_name = ''; // FIRST NAME
	$last_name = ''; // LAST NAME
	$email = ''; // EMAIL
	$email2 = ''; // EMAIL 2
	$password = ''; // PASSWORD
	$password2 = ''; // PASSWORD 2
	$date = ''; // SIGN UP DATE
	$error_array = array(); // HOLD ERROR MESSAGES

	// IF REGISTER BUTTON IS PRESSED
	if (isset($_POST['register_button'])) {

		// ASSIGNING REG_FNAME FORM VALUE TO $FIRST_NAME VARIABLE
		$first_name = strip_tags($_POST['reg_fname']); // REMOVE HTML TAGS
		$first_name = str_replace(' ', '', $first_name); // REMOVE SPACES
		$first_name = ucfirst(strtolower($first_name)); // CAPITALIZE FIRST LETTER ONLY
		$_SESSION['reg_fname'] = $first_name; // STORES FIRST NAME INTO SESSION VARIABLE

		// ASSIGNING REG_LNAME FORM VALUE TO $LAST_NAME VARIABLE
		$last_name = strip_tags($_POST['reg_lname']); // REMOVE HTML TAGS
		$last_name = str_replace(' ', '', $last_name); // REMOVE SPACES
		$last_name = ucfirst(strtolower($last_name)); // CAPITALIZE FIRST LETTER ONLY
		$_SESSION['reg_lname'] = $last_name; // STORES LAST NAME INTO SESSION VARIABLE

		// ASSIGNING REG_EMAIL FORM VALUE TO $EMAIL VARIABLE
		$email = strip_tags($_POST['reg_email']); // REMOVE HTML TAGS
		$email = str_replace(' ', '', $email); // REMOVE SPACES
		$email = strtolower($email); // LOWERCASE ALL EMAIL LETTERS
		$_SESSION['reg_email'] = $email; // STORES EMAIL INTO SESSION VARIABLE

		// ASSIGNING REG_EMAIL2 FORM VALUE TO $EMAIL2 VARIABLE
		$email2 = strip_tags($_POST['reg_email2']); // REMOVE HTML TAGS
		$email2 = str_replace(' ', '', $email2); // REMOVE SPACES
		$email2 = strtolower($email2); // LOWERCASE ALL EMAIL LETTERS
		$_SESSION['reg_email2'] = $email2; // STORES EMAIL2 INTO SESSION VARIABLE

		// ASSIGNING REG_PASSWORD FORM VALUE TO $PASSWORD VARIABLE
		$password = strip_tags($_POST['reg_password']); // REMOVE HTML TAGS

		// ASSIGNING REG_PASSWORD2 FORM VALUE TO $PASSWORD2 VARIABLE
		$password2 = strip_tags($_POST['reg_password2']); // REMOVE HTML TAGS

		// ASSIGNING USER CREATION DATE (EX. 2018-10-31)
		$date = date('Y-m-d');

		// CHECK IF EMAIL AND EMAIL2 MATCH
		if ($email == $email2) {
			// CHECK IF EMAIL IS IN PROPER FORMAT
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				// ASSIGN PROPERLY FORMATTED EMAIL TO $EMAIL VARIABLE
				$email = filter_var($email, FILTER_VALIDATE_EMAIL);

				// CHECK IF EMAIL IS ALREADY REGISTERED
				$e_check = mysqli_query($connection, "SELECT email FROM users WHERE email='$email'");
				// COUNT THE NUMBER OF ROWS RETURNED
				$num_rows = mysqli_num_rows($e_check);

				// CHECK IF QUERY RETURNS ANY ROWS (EMAIL TAKEN)
				if($num_rows > 0) {
					array_push($error_array, 'email in use');
				}
				// INPROPER FORMAT ERROR
			} else {
				array_push($error_array, 'invalid format');
			}
			// UNMATCHING EMAIL ERROR
		} else {
			array_push($error_array, 'emails do not match');
		}

		// CHECK FIRST NAME LENGTH
		if (strlen($first_name) > 25 || strlen($first_name) < 2) {
			array_push($error_array, 'first name length');
		}
		// CHECK LAST NAME LENGTH
		if (strlen($last_name) > 25 || strlen($last_name) < 2) {
			array_push($error_array, 'last name length');
		}

		// CHECK FOR MATCHING PASSWORDS
		if ($password != $password2) {
			array_push($error_array, 'passwords do not match');
		} else {
			// CHECK IF PASSWORD USES ENGLISH LETTERS (ADDED SPECIAL CHARACTERS)
			if (preg_match('/[^A-Za-z0-9\.\+!@#$%^&*()]/', $password)) {
				array_push($error_array, 'password characters');
			}
		}

		// CHECK PASSWORD LENGTH
		if (strlen($password) > 30 || strlen($password) < 5) {
			array_push($error_array, 'password length');
		}

		// IF THERE ARE NO ERRORS IN USER SIGN UP DETAILS...
		if (empty($error_array)) {

			// ENCRYPT PASSWORD BEFORE SENT TO DATABASE
			$password = md5(md5($email).$password);
			// GENERATE USERNAME BY CONCATENATING FIRST AND LAST NAME
			$username = strtolower($first_name . '_' . $last_name);
			// QUERY TO CHECK IF USERNAME IS ALREADY TAKEN
			$check_username_query = mysqli_query($connection, "SELECT username FROM users WHERE username='$username'");

			$i = 0;
			// IF USERNAME ALREADY EXISTS ADD NUMBER TO CREATE NEW USERNAME
			while (mysqli_num_rows($check_username_query) != 0) {
				$i++; // ADD 1 TO $I AND CONCATENATE TO USERNAME
				$username = $username . '_' . $i;
				// QUERY TO CHECK USERNAME EXISTENCE AGAIN
				$check_username_query = mysqli_query($connection, "SELECT username FROM users WHERE username='$username'");
			}

			// RANDOM DEFAULT PROFILE PICTURE ASSIGNMENT
			$rand = rand(1, 5); // GENERATE NUMBER BETWEEN 1 AND 5
			// ASSIGN PROFILE PIC THAT CORRESPONDS WITH NUMBER
			if ($rand == 1) {
				$profile_pic = 'assets/img/profile_pics/defaults/head_purple.png';
			} else if ($rand == 2) {
				$profile_pic = 'assets/img/profile_pics/defaults/head_red.png';
			} else if ($rand == 3) {
				$profile_pic = 'assets/img/profile_pics/defaults/head_yellow.png';
			} else if ($rand == 4) {
				$profile_pic = 'assets/img/profile_pics/defaults/head_green.png';
			} else if ($rand == 5) {
				$profile_pic = 'assets/img/profile_pics/defaults/head_blue.png';
			}

			// INSERT NEW USER VALUES INTO DATABASE
			$query = mysqli_query($connection, "INSERT INTO users VALUES ('', '$first_name', '$last_name', '$username', '$email', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");

			// PUSH SUCCESSFUL SIGN UP MESSAGE TO $ERROR_ARRAY
			array_push($error_array, 'successful signup');

			// CLEAR SESSION VARIABLES
			$_SESSION['reg_fname'] = '';
			$_SESSION['reg_lname'] = '';
			$_SESSION['reg_email'] = '';
			$_SESSION['reg_email2'] = '';
		}
	}
?>