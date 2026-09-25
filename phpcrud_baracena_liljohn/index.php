```php
<?php

include 'auth.php';
include 'database.php';


/*
============================================================
GET STUDENTS
============================================================
*/

$query = "
    SELECT id, firstname, lastname
    FROM students
    ORDER BY id DESC
";

$result = $conn->query($query);


if (!$result) {

    die(
        "Database error: "
        . htmlspecialchars($conn->error)
    );

}


$students = [];


while ($row = $result->fetch_assoc()) {

    $students[] = $row;

}


$studentCount = count($students);


/*
============================================================
USERNAME
============================================================
*/

$username = $_SESSION['username'] ?? 'User';

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
        Student Management System
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


    <!-- ICONS -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- YOUR MASTER CSS -->

    <link
        rel="stylesheet"
        href="style.css?v=30"
    >


    <style>

        /*
        ========================================================
        DASHBOARD FIX
        These styles make sure the dashboard stays organized
        even if older CSS exists in style.css.
        ========================================================
        */

        .dashboard-page {

            min-height: 100vh;

            padding: 35px 20px 25px;

        }


        .dashboard-container {

            width: 100%;

            max-width: 1180px;

            margin: 0 auto;

        }


        /* ====================================================
           HEADER
        ==================================================== */

        .dashboard-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 25px;

            padding: 22px 26px;

            margin-bottom: 22px;

            border-radius: 24px;

            background:
                rgba(255,255,255,0.72);

            border:
                1px solid
                rgba(255,255,255,0.85);

            box-shadow:
                0 18px 45px
                rgba(54,91,61,0.10);

            backdrop-filter:
                blur(20px);

            -webkit-backdrop-filter:
                blur(20px);

        }


        .dashboard-brand {

            display: flex;

            align-items: center;

            gap: 14px;

            min-width: 0;

        }


        .dashboard-logo {

            width: 52px;

            height: 52px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 16px;

            background:
                linear-gradient(
                    135deg,
                    #365b3d,
                    #789873
                );

            color: white;

            font-size: 23px;

            box-shadow:
                0 10px 25px
                rgba(54,91,61,0.20);

        }


        .dashboard-brand-text {

            min-width: 0;

        }


        .dashboard-brand-text .small-title {

            margin: 0 0 2px;

            color: #718074;

            font-size: 10px;

            font-weight: 600;

            letter-spacing: 1.2px;

            text-transform: uppercase;

        }


        .dashboard-brand-text h1 {

            margin: 0;

            color: #294a31;

            font-size: 23px;

            font-weight: 700;

            line-height: 1.2;

        }


        .dashboard-brand-text p {

            margin: 4px 0 0;

            color: #7a897c;

            font-size: 11px;

        }


        /* ====================================================
           HEADER RIGHT
        ==================================================== */

        .dashboard-user {

            display: flex;

            align-items: center;

            gap: 12px;

            flex-shrink: 0;

        }


        .welcome-box {

            text-align: right;

        }


        .welcome-box span {

            display: block;

            color: #89958a;

            font-size: 10px;

        }


        .welcome-box strong {

            display: block;

            color: #365b3d;

            font-size: 13px;

            font-weight: 600;

        }


        .header-actions {

            display: flex;

            align-items: center;

            gap: 8px;

        }


        .header-btn {

            min-height: 40px;

            padding: 0 15px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            border-radius: 11px;

            font-family: Poppins, sans-serif;

            font-size: 11px;

            font-weight: 600;

            text-decoration: none;

            border: none;

            cursor: pointer;

            transition: 0.25s ease;

        }


        .header-btn:hover {

            transform:
                translateY(-2px);

        }


        .add-student-btn {

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #365b3d,
                    #648b68
                );

            box-shadow:
                0 8px 18px
                rgba(54,91,61,0.18);

        }


        .logout-btn {

            color: #6d4c4c;

            background:
                rgba(255,255,255,0.65);

            border:
                1px solid
                rgba(160,100,100,0.13);

        }


        .logout-btn:hover {

            color: #8a4747;

            background:
                #f8ecea;

        }


        /* ====================================================
           STATS
        ==================================================== */

        .stats-grid {

            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 16px;

            margin-bottom: 22px;

        }


        .stat-card {

            min-width: 0;

            padding: 20px;

            border-radius: 20px;

            background:
                rgba(255,255,255,0.74);

            border:
                1px solid
                rgba(255,255,255,0.88);

            box-shadow:
                0 14px 35px
                rgba(54,91,61,0.08);

            backdrop-filter:
                blur(18px);

            -webkit-backdrop-filter:
                blur(18px);

            transition:
                0.25s ease;

        }


        .stat-card:hover {

            transform:
                translateY(-4px);

            box-shadow:
                0 18px 40px
                rgba(54,91,61,0.13);

        }


        .stat-top {

            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 12px;

        }


        .stat-icon {

            width: 38px;

            height: 38px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 12px;

            color: #426b49;

            background:
                #e3eee0;

            font-size: 16px;

        }


        .stat-status {

            width: 8px;

            height: 8px;

            border-radius: 50%;

            background:
                #5d9b66;

            box-shadow:
                0 0 0 5px
                rgba(93,155,102,0.10);

        }


        .stat-label {

            margin: 0 0 4px;

            color: #7a897c;

            font-size: 11px;

        }


        .stat-value {

            margin: 0;

            color: #304a36;

            font-size: 21px;

            font-weight: 700;

        }


        /* ====================================================
           STUDENT SECTION
        ==================================================== */

        .student-card {

            overflow: hidden;

            border-radius: 24px;

            background:
                rgba(255,255,255,0.76);

            border:
                1px solid
                rgba(255,255,255,0.88);

            box-shadow:
                0 20px 55px
                rgba(54,91,61,0.10);

            backdrop-filter:
                blur(20px);

            -webkit-backdrop-filter:
                blur(20px);

        }


        .student-card-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            padding: 22px 25px;

            border-bottom:
                1px solid
                rgba(54,91,61,0.08);

        }


        .section-title {

            min-width: 0;

        }


        .section-title .eyebrow {

            margin: 0 0 3px;

            color: #789078;

            font-size: 9px;

            font-weight: 600;

            letter-spacing: 1.2px;

            text-transform: uppercase;

        }


        .section-title h2 {

            margin: 0;

            color: #304a36;

            font-size: 22px;

            font-weight: 700;

        }


        .section-title p {

            margin: 4px 0 0;

            color: #879288;

            font-size: 11px;

        }


        .record-count {

            flex-shrink: 0;

            padding: 8px 12px;

            border-radius: 10px;

            color: #4d704f;

            background:
                #e7efe4;

            font-size: 10px;

            font-weight: 600;

        }


        /* ====================================================
           TABLE
        ==================================================== */

        .student-table-wrapper {

            width: 100%;

            overflow-x: auto;

        }


        .student-table {

            width: 100%;

            margin: 0;

            border-collapse: collapse;

        }


        .student-table thead th {

            padding: 14px 25px;

            color: #718174;

            background:
                rgba(226,236,222,0.48);

            border-bottom:
                1px solid
                rgba(54,91,61,0.08);

            font-size: 10px;

            font-weight: 700;

            letter-spacing: .4px;

            text-transform: uppercase;

            white-space: nowrap;

        }


        .student-table tbody td {

            padding: 15px 25px;

            color: #405044;

            border-bottom:
                1px solid
                rgba(54,91,61,0.06);

            font-size: 12px;

            vertical-align: middle;

        }


        .student-table tbody tr {

            transition:
                0.2s ease;

        }


        .student-table tbody tr:hover {

            background:
                rgba(226,236,222,0.30);

        }


        .student-number {

            width: 50px;

            color: #9aa49b !important;

            font-size: 10px !important;

        }


        .student-name {

            color: #304a36 !important;

            font-weight: 600;

        }


        .student-id {

            color: #91a095;

            font-size: 10px;

        }


        .action-buttons {

            display: flex;

            align-items: center;

            gap: 7px;

        }


        .action-btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 5px;

            min-height: 32px;

            padding: 0 10px;

            border: none;

            border-radius: 9px;

            font-family: Poppins, sans-serif;

            font-size: 10px;

            font-weight: 600;

            cursor: pointer;

            text-decoration: none;

            transition: 0.2s ease;

        }


        .action-btn:hover {

            transform:
                translateY(-1px);

        }


        .edit-action {

            color: #4d704f;

            background:
                #e5efe2;

        }


        .edit-action:hover {

            color: #365b3d;

            background:
                #d7e7d3;

        }


        .delete-action {

            color: #9a5656;

            background:
                #f5e6e4;

        }


        .delete-action:hover {

            color: #813f3f;

            background:
                #efd6d3;

        }


        /* ====================================================
           EMPTY STATE
        ==================================================== */

        .empty-state {

            padding: 55px 20px;

            text-align: center;

        }


        .empty-icon {

            width: 60px;

            height: 60px;

            margin:
                0 auto 15px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 18px;

            color: #66836a;

            background:
                #e4eee1;

            font-size: 23px;

        }


        .empty-state h3 {

            margin: 0 0 5px;

            color: #3d5542;

            font-size: 16px;

        }


        .empty-state p {

            margin: 0;

            color: #8b958c;

            font-size: 11px;

        }


        /* ====================================================
           FOOTER
        ==================================================== */

        .dashboard-footer {

            padding: 18px 5px 0;

            text-align: center;

            color: #879287;

            font-size: 9px;

        }


        /* ====================================================
           MODAL
        ==================================================== */

        .modal-content {

            border: none !important;

            border-radius: 22px !important;

            overflow: hidden;

            background:
                rgba(248,250,246,0.97) !important;

            box-shadow:
                0 30px 80px
                rgba(45,70,50,0.22) !important;

        }


        .modal-header {

            padding: 20px 23px;

            border-bottom:
                1px solid
                rgba(54,91,61,0.08) !important;

        }


        .modal-title {

            color: #304a36;

            font-size: 18px;

            font-weight: 700;

        }


        .modal-body {

            padding: 23px;

        }


        .modal-footer {

            padding: 16px 23px;

            border-top:
                1px solid
                rgba(54,91,61,0.08) !important;

        }


        .modal-label {

            display: block;

            margin-bottom: 7px;

            color: #506052;

            font-size: 11px;

            font-weight: 600;

        }


        .modal-input {

            width: 100%;

            height: 46px;

            padding: 0 13px;

            border:
                1px solid
                rgba(54,91,61,0.13);

            border-radius: 11px;

            outline: none;

            color: #405044;

            background:
                rgba(255,255,255,0.72);

            font-family: Poppins, sans-serif;

            font-size: 12px;

            transition: .25s ease;

        }


        .modal-input:focus {

            border-color:
                rgba(70,111,77,.48);

            background:
                white;

            box-shadow:
                0 0 0 4px
                rgba(75,115,80,.07);

        }


        .modal-cancel {

            min-height: 40px;

            padding: 0 16px;

            border: none;

            border-radius: 10px;

            color: #667269;

            background:
                #e8eee5;

            font-family: Poppins, sans-serif;

            font-size: 11px;

            font-weight: 600;

        }


        .modal-save {

            min-height: 40px;

            padding: 0 17px;

            border: none;

            border-radius: 10px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #365b3d,
                    #789873
                );

            font-family: Poppins, sans-serif;

            font-size: 11px;

            font-weight: 600;

            box-shadow:
                0 8px 18px
                rgba(54,91,61,.18);

        }


        /* ====================================================
           RESPONSIVE
        ==================================================== */

        @media (max-width: 850px) {

            .dashboard-header {

                align-items:
                    flex-start;

                flex-direction:
                    column;

            }


            .dashboard-user {

                width: 100%;

                justify-content:
                    space-between;

            }


            .welcome-box {

                text-align:
                    left;

            }

        }


        @media (max-width: 700px) {

            .dashboard-page {

                padding:
                    18px 12px;

            }


            .stats-grid {

                grid-template-columns:
                    1fr;

            }


            .student-card-header {

                align-items:
                    flex-start;

                flex-direction:
                    column;

            }


            .student-table thead th,
            .student-table tbody td {

                padding-left:
                    15px;

                padding-right:
                    15px;

            }

        }


        @media (max-width: 480px) {

            .dashboard-brand-text h1 {

                font-size:
                    19px;

            }


            .dashboard-brand-text p {

                font-size:
                    10px;

            }


            .dashboard-user {

                align-items:
                    flex-start;

                flex-direction:
                    column;

            }


            .header-actions {

                width:
                    100%;

            }


            .header-btn {

                flex:
                    1;

            }


            .dashboard-logo {

                width:
                    45px;

                height:
                    45px;

            }

        }

    </style>

</head>


<body>


<div class="dashboard-page">


    <div class="dashboard-container">


        <!-- =================================================
             HEADER
        ================================================== -->

        <header class="dashboard-header">


            <!-- BRAND -->

            <div class="dashboard-brand">


                <div class="dashboard-logo">

                    <i class="bi bi-mortarboard-fill"></i>

                </div>


                <div class="dashboard-brand-text">

                    <p class="small-title">
                        Student System
                    </p>

                    <h1>
                        Student Management
                    </h1>

                    <p>
                        Manage your student records in one place.
                    </p>

                </div>


            </div>


            <!-- USER -->

            <div class="dashboard-user">


                <div class="welcome-box">

                    <span>
                        Welcome back
                    </span>

                    <strong>
                        <?= htmlspecialchars(
                            $username,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </strong>

                </div>


                <div class="header-actions">


                    <button
                        type="button"
                        class="header-btn add-student-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#addStudentModal"
                    >

                        <i class="bi bi-plus-lg"></i>

                        Add Student

                    </button>


                    <a
                        href="logout.php"
                        class="header-btn logout-btn"
                    >

                        <i class="bi bi-box-arrow-right"></i>

                        Logout

                    </a>


                </div>


            </div>


        </header>


        <!-- =================================================
             STATISTICS
        ================================================== -->

        <section class="stats-grid">


            <!-- TOTAL STUDENTS -->

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon">

                        <i class="bi bi-people-fill"></i>

                    </div>

                    <div class="stat-status"></div>

                </div>


                <p class="stat-label">
                    Total Students
                </p>


                <p class="stat-value">
                    <?= $studentCount ?>
                </p>

            </div>


            <!-- SYSTEM STATUS -->

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon">

                        <i class="bi bi-activity"></i>

                    </div>

                    <div class="stat-status"></div>

                </div>


                <p class="stat-label">
                    System Status
                </p>


                <p class="stat-value">
                    Online
                </p>

            </div>


            <!-- DATABASE -->

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon">

                        <i class="bi bi-database-check"></i>

                    </div>

                    <div class="stat-status"></div>

                </div>


                <p class="stat-label">
                    Database
                </p>


                <p class="stat-value">
                    Connected
                </p>

            </div>


        </section>


        <!-- =================================================
             STUDENT LIST
        ================================================== -->

        <section class="student-card">


            <!-- SECTION HEADER -->

            <div class="student-card-header">


                <div class="section-title">

                    <p class="eyebrow">
                        Student Records
                    </p>

                    <h2>
                        Student List
                    </h2>

                    <p>
                        View, edit, and manage student information.
                    </p>

                </div>


                <div class="record-count">

                    <?= $studentCount ?>

                    <?= $studentCount == 1
                        ? 'record'
                        : 'records'
                    ?>

                </div>


            </div>


            <!-- TABLE -->

            <?php if ($studentCount > 0): ?>


                <div class="student-table-wrapper">


                    <table class="student-table">


                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    First Name
                                </th>

                                <th>
                                    Last Name
                                </th>

                                <th>
                                    Student ID
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php foreach (
                                $students
                                as $index => $student
                            ): ?>


                                <tr>


                                    <td class="student-number">

                                        <?= $index + 1 ?>

                                    </td>


                                    <td class="student-name">

                                        <?= htmlspecialchars(
                                            $student['firstname'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </td>


                                    <td class="student-name">

                                        <?= htmlspecialchars(
                                            $student['lastname'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </td>


                                    <td class="student-id">

                                        #<?= (int) $student['id'] ?>

                                    </td>


                                    <td>


                                        <div class="action-buttons">


                                            <!-- EDIT -->

                                            <button
                                                type="button"
                                                class="action-btn edit-action"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editStudentModal"
                                                data-id="<?= (int) $student['id'] ?>"
                                                data-firstname="<?= htmlspecialchars(
                                                    $student['firstname'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                                data-lastname="<?= htmlspecialchars(
                                                    $student['lastname'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                            >

                                                <i class="bi bi-pencil-fill"></i>

                                                Edit

                                            </button>


                                            <!-- DELETE -->

                                            <a
                                                href="delete.php?id=<?= (int) $student['id'] ?>"
                                                class="action-btn delete-action"
                                                onclick="
                                                    return confirm(
                                                        'Are you sure you want to delete this student?'
                                                    );
                                                "
                                            >

                                                <i class="bi bi-trash3-fill"></i>

                                                Delete

                                            </a>


                                        </div>


                                    </td>


                                </tr>


                            <?php endforeach; ?>


                        </tbody>


                    </table>


                </div>


            <?php else: ?>


                <!-- EMPTY -->

                <div class="empty-state">


                    <div class="empty-icon">

                        <i class="bi bi-people"></i>

                    </div>


                    <h3>
                        No Students Yet
                    </h3>


                    <p>
                        Click "Add Student" to create your first record.
                    </p>


                </div>


            <?php endif; ?>


        </section>


        <!-- =================================================
             FOOTER
        ================================================== -->

        <footer class="dashboard-footer">

            Student Management System
            &nbsp;•&nbsp;
            PHP CRUD

        </footer>


    </div>

</div>


<!-- =========================================================
     ADD STUDENT MODAL
========================================================= -->

<div
    class="modal fade"
    id="addStudentModal"
    tabindex="-1"
    aria-hidden="true"
>


    <div class="modal-dialog modal-dialog-centered">


        <div class="modal-content">


            <div class="modal-header">


                <h5 class="modal-title">

                    <i class="bi bi-person-plus-fill me-2"></i>

                    Add Student

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>


            </div>


            <form
                action="insert.php"
                method="POST"
            >


                <div class="modal-body">


                    <div class="mb-3">


                        <label
                            for="addFirstname"
                            class="modal-label"
                        >

                            First Name

                        </label>


                        <input
                            type="text"
                            id="addFirstname"
                            name="firstname"
                            class="modal-input"
                            placeholder="Enter first name"
                            maxlength="50"
                            required
                        >


                    </div>


                    <div class="mb-2">


                        <label
                            for="addLastname"
                            class="modal-label"
                        >

                            Last Name

                        </label>


                        <input
                            type="text"
                            id="addLastname"
                            name="lastname"
                            class="modal-input"
                            placeholder="Enter last name"
                            maxlength="50"
                            required
                        >


                    </div>


                </div>


                <div class="modal-footer">


                    <button
                        type="button"
                        class="modal-cancel"
                        data-bs-dismiss="modal"
                    >

                        Cancel

                    </button>


                    <button
                        type="submit"
                        class="modal-save"
                    >

                        <i class="bi bi-check-lg me-1"></i>

                        Save Student

                    </button>


                </div>


            </form>


        </div>

    </div>

</div>


<!-- =========================================================
     EDIT STUDENT MODAL
========================================================= -->

<div
    class="modal fade"
    id="editStudentModal"
    tabindex="-1"
    aria-hidden="true"
>


    <div class="modal-dialog modal-dialog-centered">


        <div class="modal-content">


            <div class="modal-header">


                <h5 class="modal-title">

                    <i class="bi bi-pencil-square me-2"></i>

                    Edit Student

                </h5>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>


            </div>


            <form
                action="update.php"
                method="POST"
            >


                <div class="modal-body">


                    <input
                        type="hidden"
                        id="editId"
                        name="id"
                    >


                    <div class="mb-3">


                        <label
                            for="editFirstname"
                            class="modal-label"
                        >

                            First Name

                        </label>


                        <input
                            type="text"
                            id="editFirstname"
                            name="firstname"
                            class="modal-input"
                            maxlength="50"
                            required
                        >


                    </div>


                    <div class="mb-2">


                        <label
                            for="editLastname"
                            class="modal-label"
                        >

                            Last Name

                        </label>


                        <input
                            type="text"
                            id="editLastname"
                            name="lastname"
                            class="modal-input"
                            maxlength="50"
                            required
                        >


                    </div>


                </div>


                <div class="modal-footer">


                    <button
                        type="button"
                        class="modal-cancel"
                        data-bs-dismiss="modal"
                    >

                        Cancel

                    </button>


                    <button
                        type="submit"
                        class="modal-save"
                    >

                        <i class="bi bi-save2-fill me-1"></i>

                        Update Student

                    </button>


                </div>


            </form>


        </div>

    </div>

</div>


<!-- BOOTSTRAP JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<!-- EDIT MODAL SCRIPT -->

<script>

document
    .getElementById('editStudentModal')
    .addEventListener(
        'show.bs.modal',
        function (event) {

            const button = event.relatedTarget;

            const id =
                button.getAttribute('data-id');

            const firstname =
                button.getAttribute('data-firstname');

            const lastname =
                button.getAttribute('data-lastname');


            document
                .getElementById('editId')
                .value = id;


            document
                .getElementById('editFirstname')
                .value = firstname;


            document
                .getElementById('editLastname')
                .value = lastname;

        }
    );

</script>


</body>

</html>
```
