```php
<?php

/*
============================================================
TEMPORARY DEBUG
Remove these 3 lines after everything is working.
============================================================
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


include 'database.php';


/*
============================================================
ALREADY LOGGED IN
============================================================
*/

if (isset($_SESSION['user_id'])) {

    header('Location: index.php');
    exit();

}


$error   = '';
$success = '';


/*
============================================================
CREATE ACCOUNT
============================================================
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $username =
        trim($_POST['username'] ?? '');


    $password =
        $_POST['password'] ?? '';


    $confirm =
        $_POST['confirm_password'] ?? '';


    /*
    --------------------------------------------------------
    VALIDATION
    --------------------------------------------------------
    */

    if (
        $username === '' ||
        $password === '' ||
        $confirm === ''
    ) {

        $error =
            'Please fill in all fields.';


    } elseif (
        strlen($username) < 3
    ) {

        $error =
            'Username must be at least 3 characters.';


    } elseif (
        strlen($password) < 6
    ) {

        $error =
            'Password must be at least 6 characters.';


    } elseif (
        $password !== $confirm
    ) {

        $error =
            'Passwords do not match.';


    } else {


        /*
        ----------------------------------------------------
        CHECK USERNAME
        ----------------------------------------------------
        */

        $checkQuery = "
            SELECT id
            FROM users
            WHERE username = ?
        ";


        $checkStmt =
            $conn->prepare($checkQuery);


        $checkStmt->bind_param(
            "s",
            $username
        );


        $checkStmt->execute();


        $checkStmt->store_result();


        /*
        ----------------------------------------------------
        USERNAME EXISTS
        ----------------------------------------------------
        */

        if ($checkStmt->num_rows > 0) {

            $error =
                'That username is already taken.';


            $checkStmt->close();


        } else {


            $checkStmt->close();


            /*
            ------------------------------------------------
            HASH PASSWORD
            ------------------------------------------------
            */

            $hashedPassword =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );


            /*
            ------------------------------------------------
            INSERT ACCOUNT
            ------------------------------------------------
            */

            $insertQuery = "
                INSERT INTO users
                (
                    username,
                    password
                )
                VALUES
                (
                    ?,
                    ?
                )
            ";


            $insertStmt =
                $conn->prepare(
                    $insertQuery
                );


            $insertStmt->bind_param(
                "ss",
                $username,
                $hashedPassword
            );


            if ($insertStmt->execute()) {

                $success =
                    'Account created! You can now log in.';

            } else {

                $error =
                    'Something went wrong: '
                    . $insertStmt->error;

            }


            $insertStmt->close();

        }

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Create Account | Student Management System
    </title>


    <!-- GOOGLE FONT -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- BOOTSTRAP -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- MASTER DESIGN -->

    <link
        rel="stylesheet"
        href="style.css?v=20"
    >

</head>


<body>


<div class="auth-shell">


    <div class="auth-split">


        <!-- =================================================
             LEFT SIDE
        ================================================== -->

        <div class="auth-visual">


            <div class="auth-visual-inner">


                <div class="auth-badge">
                    🌱
                </div>


                <h2>
                    Join the System
                </h2>


                <p>
                    Create your account and start managing
                    student records in one simple dashboard.
                </p>


                <ul class="auth-feature-list">

                    <li>

                        <span class="auth-feature-icon">
                            ✓
                        </span>

                        Quick and easy registration

                    </li>


                    <li>

                        <span class="auth-feature-icon">
                            ✓
                        </span>

                        Passwords are securely hashed

                    </li>


                    <li>

                        <span class="auth-feature-icon">
                            ✓
                        </span>

                        Access your dashboard anytime

                    </li>

                </ul>

            </div>

        </div>


        <!-- =================================================
             RIGHT SIDE
        ================================================== -->

        <div class="auth-form-panel">


            <div class="auth-form-inner">


                <h1>
                    Create Account
                </h1>


                <p>
                    Sign up to start managing student records.
                </p>


                <!-- ERROR -->

                <?php if ($error): ?>

                    <div class="auth-alert">

                        <?php

                        echo htmlspecialchars(
                            $error,
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        ?>

                    </div>

                <?php endif; ?>


                <!-- SUCCESS -->

                <?php if ($success): ?>

                    <div class="auth-alert auth-alert-success">

                        <?php

                        echo htmlspecialchars(
                            $success,
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        ?>

                    </div>

                <?php endif; ?>


                <!-- REGISTER FORM -->

                <form
                    action="register.php"
                    method="POST"
                >


                    <!-- USERNAME -->

                    <div class="mb-3">

                        <label
                            class="form-label"
                            for="username"
                        >
                            Username
                        </label>


                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control custom-input"
                            placeholder="Choose a username"
                            maxlength="50"
                            autocomplete="username"
                            required
                        >

                    </div>


                    <!-- PASSWORD -->

                    <div class="mb-3">

                        <label
                            class="form-label"
                            for="password"
                        >
                            Password
                        </label>


                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control custom-input"
                            placeholder="At least 6 characters"
                            autocomplete="new-password"
                            required
                        >

                    </div>


                    <!-- CONFIRM PASSWORD -->

                    <div class="mb-3">

                        <label
                            class="form-label"
                            for="confirm_password"
                        >
                            Confirm Password
                        </label>


                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            class="form-control custom-input"
                            placeholder="Re-enter your password"
                            autocomplete="new-password"
                            required
                        >

                    </div>


                    <!-- CREATE BUTTON -->

                    <button
                        type="submit"
                        class="btn save-btn auth-submit-btn"
                    >

                        Create Account

                    </button>


                </form>


                <!-- LOGIN -->

                <div class="auth-switch">

                    Already have an account?

                    <a href="login.php">
                        Log in
                    </a>

                </div>


            </div>

        </div>


    </div>

</div>


</body>

</html>
```
