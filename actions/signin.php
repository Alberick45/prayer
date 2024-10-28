<?php 
require('config.php');

if (isset($_POST['signup'])){
    $full_name = !empty($_POST['fullname']) ? $_POST['fullname'] : null;
    $email = !empty($_POST['email']) ? $_POST['email'] : null;
    $username = !empty($_POST['username']) ? $_POST['username'] : null;
    $password = !empty($_POST['password']) ? $_POST['password'] : null;

    // Check if the password length is at least 8 characters
    if (strlen($password) < 8) {
        echo "<script>
                alert('Password must be at least 8 characters long');
                window.location.href = '../signup.html';
              </script>";
        exit();
    }

    // Check if the username already exists
    $checkUserQuery = "SELECT user_id FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkUserQuery);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Username is taken
        echo "<script>
                alert('Username already taken');
                window.location.href = '../signup.html';
              </script>";
        $stmt->close();
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the query to insert the new user
        $insertQuery = "INSERT INTO users (fullname, email, username, password, last_login, date_joined) 
                        VALUES (?, ?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssss", $full_name, $email, $username, $hashedPassword);

        // Execute the insert query
        if ($stmt->execute()) {
            // Retrieve the newly inserted user ID
            $userIdQuery = "SELECT user_id,profile_picture FROM users WHERE username = ?";
            $stmt = $conn->prepare($userIdQuery);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_id = $row["user_id"];
                $profile_pic = $row["profile_picture"];

                // Store session variables
                $_SESSION["user_id"] = $user_id;
                $_SESSION["username"] = $username;
                $_SESSION["profile_pic"] = $profile_pic;
                $_SESSION['message'] = "New user " . $username . " created successfully!";

                // Redirect to the index page
                header("Location: ../index.php");
                exit();
            }
        } else {
            // Handle SQL execution errors
            echo "Error: " . $stmt->error;
        }
    }

}



if (isset($_POST['signin'])){
            session_start();
            require_once("config.php");

                // Assign form inputs to variables or default to NULL
                $email = !empty($_POST['email']) ? $_POST['email'] : null;
                $password = !empty($_POST['password']) ? $_POST['password'] : null;

                // Check password length
                if (strlen($password) < 8) {
                    echo "<script>
                            alert('Password must be at least 8 characters long');
                            window.location.href = '../signup.html';
                        </script>";
                    exit();
                }

                // Prepare the SQL query to check if the user exists
                $checkUserQuery = "SELECT user_id, username, email, password,profile_picture FROM users WHERE email = ?";
                $stmt = $conn->prepare($checkUserQuery);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $results = $stmt->get_result();

                if ($results->num_rows > 0) {
                    // User exists, fetch the data
                    $row = $results->fetch_assoc();
                    $user_id = $row['user_id'];
                    $stored_password = $row['password'];

                    // Verify the password
                    if (password_verify($password, $stored_password)) {
                        // Store session variables
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['username'] = $row['username'];
                        $_SESSION['profile_pic'] = $row['profile_picture'];
                        $_SESSION['message'] = "User " . $row['username'] . " logged in successfully!";

                        // Update last login time
                        $updateQuery = "UPDATE users SET last_login = NOW() WHERE user_id = ?";
                        $updateStmt = $conn->prepare($updateQuery);
                        $updateStmt->bind_param("i", $user_id);
                        $updateStmt->execute();

                        // Redirect to the dashboard or homepage
                        header("Location: ../index.php");
                        exit();
                    } else {
                        echo "<script>
                                alert('Incorrect password');
                                window.location.href = '../signup.html';
                            </script>";
                    }
                } else {
                    echo "<script>
                            alert('User not found');
                            window.location.href = '../signup.html';
                        </script>";
                }
            }

