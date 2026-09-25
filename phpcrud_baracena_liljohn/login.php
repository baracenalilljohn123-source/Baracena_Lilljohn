<?php

session_start();

include 'database.php';


/*
============================================================
ALREADY LOGGED IN
============================================================
*/

if (isset($_SESSION['user_id'])) {

    header("Location: index.php");
    exit();

}


$error = "";


/*
============================================================
LOGIN PROCESS
============================================================
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";


    if ($username === "" || $password === "") {

        $error = "Please enter your username and password.";

    } else {


        /*
        ----------------------------------------------------
        FIND USER
        ----------------------------------------------------
        */

        $query = "
            SELECT id, username, password
            FROM users
            WHERE username = ?
            LIMIT 1
        ";


        $stmt = $conn->prepare($query);


        if (!$stmt) {

            $error = "Something went wrong. Please try again.";

        } else {

            $stmt->bind_param("s", $username);

            $stmt->execute();

            $result = $stmt->get_result();


            /*
            ------------------------------------------------
            USER FOUND
            ------------------------------------------------
            */

            if ($result && $result->num_rows === 1) {

                $user = $result->fetch_assoc();


                /*
                ------------------------------------------------
                VERIFY PASSWORD
                ------------------------------------------------
                */

                if (
                    password_verify(
                        $password,
                        $user["password"]
                    )
                ) {


                    /*
                    --------------------------------------------
                    LOGIN SUCCESS
                    --------------------------------------------
                    */

                    $_SESSION["user_id"] =
                        $user["id"];

                    $_SESSION["username"] =
                        $user["username"];


                    $stmt->close();


                    header("Location: index.php");

                    exit();


                } else {

                    $error =
                        "Invalid username or password.";

                }

            } else {

                $error =
                    "Invalid username or password.";

            }


            $stmt->close();

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
        Login | Student Management System
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
                    🎓
                </div>


                <h2>
                    Welcome Back
                </h2>


                <p>
                    Sign in to your Student Management
                    System and continue managing your
                    student records.
                </p>


                <ul class="auth-feature-list">

                    <li>

                        <span class="auth-feature-icon">
                            ✓
                        </span>

                        Secure account access

                    </li>


                    <li>

                        <span class="auth-feature-icon">
                            ✓
                        </span>

                        Manage student records

                    </li>


                    <li>

                        <span class="auth-feature-icon">
                            ✓
                        </span>

                        Simple and organized dashboard

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
                    Welcome Back
                </h1>


                <p>
                    Log in to access your dashboard.
                </p>


                <!-- ERROR -->

                <?php if (!empty($error)): ?>

                    <div class="auth-alert">

                        <?= htmlspecialchars(
                            $error,
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>

                    </div>

                <?php endif; ?>


                <!-- LOGIN FORM -->

                <form
                    action="login.php"
                    method="POST"
                >


                    <!-- USERNAME -->

                    <div class="mb-3">

                        <label
                            for="username"
                            class="form-label"
                        >
                            Username
                        </label>


                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control custom-input"
                            placeholder="Enter your username"
                            maxlength="50"
                            autocomplete="username"
                            required
                        >

                    </div>


                    <!-- PASSWORD -->

                    <div class="mb-3">

                        <label
                            for="password"
                            class="form-label"
                        >
                            Password
                        </label>


                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control custom-input"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                    </div>


                    <!-- LOGIN BUTTON -->

                    <button
                        type="submit"
                        class="btn save-btn auth-submit-btn"
                    >

                        Login to Dashboard

                    </button>


                </form>


                <!-- CREATE ACCOUNT -->

                <div class="auth-switch">

                    Don't have an account?

                    <a href="register.php">
                        Create Account
                    </a>

                </div>


            </div>

        </div>


    </div>

</div>


</body>

</html>