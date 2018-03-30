<?php

	class User {
		// PRIVATE VARIABLES
		private $user;
		private $connection;

		// CREATE PUBLIC VARIABLES
		public function __construct($connection, $user) {
			// CONNECTION VARIABLE
			$this->connection = $connection;
			// DATABASE QUERY
			$user_details_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$user'");
			// QUERY RESULT ARRAY
			$this->user = mysqli_fetch_array($user_details_query);
		}

		// GET USERNAME FUNCTION
		public function getUsername() {
			// RETURN USERNAME
			return $this->user['username'];
		}

		// GET NUMBER OF POSTS FUNCTION
		public function getNumPosts() {
			// USERNAME VARIABLE
			$username = $this->user['username'];
			// DATABASE QUERY (NUMBER OF POSTS)
			$query = mysqli_query($this->connection, "SELECT num_posts FROM users WHERE username='$username'");
			// STORE NUMBER OF POSTS IN ARRAY
			$row = mysqli_fetch_array($query);
			// RETURN NUMBER OF POSTS
			return $row['num_posts'];
		}

		// GET FIRST AND LAST NAME FUNCTION
		public function getFirstAndLastName() {
			// USERNAME VARIABLE
			$username = $this->user['username'];
			// DATABASE QUERY (FIRST AND LAST NAME)
			$query = mysqli_query($this->connection, "SELECT first_name, last_name FROM users WHERE username='$username'");
			// STORE FIRST AND LAST NAME IN ARRAY
			$row = mysqli_fetch_array($query);
			// RETURN FIRST AND LAST NAME
			return $row['first_name'] . ' ' . $row['last_name'];
		}

		// GET PROFILE PICTURE FUNCTION
		public function getProfilePic() {
			// USERNAME VARIABLE
			$username = $this->user['username'];
			// DATABASE QUERY (PROFILE PIC)
			$query = mysqli_query($this->connection, "SELECT profile_pic FROM users WHERE username='$username'");
			// STORE FIRST AND LAST NAME IN ARRAY
			$row = mysqli_fetch_array($query);
			// RETURN FIRST AND LAST NAME
			return $row['profile_pic'];
		}

		// GET USER ACCOUNT CLOSED DATA FUNCTION
		public function isClosed() {
			// USERNAME VARIABLE
			$username = $this->user['username'];
			// DATABASE QUERY (ACCOUNT CLOSED)
			$query = mysqli_query($this->connection, "SELECT user_closed FROM users WHERE username='$username'");
			// STORE ACCOUNT CLOSED INFO IN ARRAY
			$row = mysqli_fetch_array($query);

			// CHECK IF USER ACCOUNT IS CLOSED
			if ($row['user_closed'] == 'yes') {
				return true;
			} else {
				return false;
			}
		}

		// CHECK IF USER IS FRIENDS WITH ANOTHER USER
		public function isFriend($username_to_check) {
			$usernameComma = ',' . $username_to_check . ',';

			// CHECK IF USER IS IN FRIEND_ARRAY OR IF USER IS YOURSELF
			if (strstr($this->user['friend_array'], $usernameComma) || $username_to_check == $this->user['username']) {

				return true;
			} else {
				return false;
			}
		}

	}

?>