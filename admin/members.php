<?php

if (!isset($_SERVER['HTTP_REFERER'])) {
    header('Location: index.php');
    exit();
}

session_start();

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION['Username'])) {
    $pageTitle = 'Members';
    include 'init.php';

    $do = $_GET['do'] ?? 'manage';

    // Manage Members Section
    if ($do == 'manage') {
        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
        <div class="container py-5">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h1 class="h4 text-primary">Manage Members</h1>
                <a href="members.php?do=add" class="btn btn-success">
                    <i class="fas fa-user-plus me-2"></i>Add New Member
                </a>
            </div>

            <?php if (isset($_SESSION['success_msg'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success_msg'] ?></div>
                <?php unset($_SESSION['success_msg']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_msg'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error_msg'] ?></div>
                <?php unset($_SESSION['error_msg']); ?>
            <?php endif; ?>

            <div class="card members-table">
                <div class="card-header bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                        </div>
                    </div>
                </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Full Name</th>
                                <th>Registration Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($rows)): ?>
                                <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <td><?= $row['UserId'] ?></td>
                                        <td><?= htmlspecialchars($row['Username']) ?></td>
                                        <td><?= htmlspecialchars($row['Email']) ?></td>
                                        <td><?= htmlspecialchars($row['FullName']) ?></td>
                                        <td><?= date('Y/m/d', strtotime($row['Date'])) ?></td>
                                        <td>
                                            <a href="members.php?do=edit&userid=<?= $row['UserId'] ?>" 
                                               class="btn btn-sm btn-success">
                                               <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="members.php?do=delete&userid=<?= $row['UserId'] ?>" 
                                               class="btn btn-sm btn-danger confirm-delete">
                                               <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                                        No members found
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

      
        <?php
    }

    // Update Member Section
    elseif ($do == 'update') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['userid'];
            $fullname = $_POST['fullname'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $newpassword = $_POST['newpassword'];
            $oldpassword = $_POST['oldpassword'];

            // Update user information
            $stmt = $con->prepare("UPDATE users SET FullName = ?, Username = ?, Email = ? WHERE UserID = ?");
            $stmt->execute([$fullname, $username, $email, $id]);

            // Update password if new password is provided
            if (!empty($newpassword)) {
                $hashedPassword = password_hash($newpassword, PASSWORD_DEFAULT);
                $stmt = $con->prepare("UPDATE users SET Password = ? WHERE UserID = ?");
                $stmt->execute([$hashedPassword, $id]);
            }

            // Update profile image
            if ($_FILES['avatar']['size'] > 0) {
                $avatar = $_FILES['avatar'];
                $avatarName = $avatar['name'];
                $avatarTmp = $avatar['tmp_name'];
                $avatarType = $avatar['type'];
                $avatarSize = $avatar['size'];

                // Check if the image is valid
                if (in_array($avatarType, ['image/jpeg', 'image/png', 'image/gif'])) {
                    // Move the image to the uploads directory
                    $avatarPath = 'uploads/' . $avatarName;
                    move_uploaded_file($avatarTmp, $avatarPath);

                    // Update the user's avatar
                    $stmt = $con->prepare("UPDATE users SET Avatar = ? WHERE UserID = ?");
                    $stmt->execute([$avatarPath, $id]);
                }
            }

            // Set success message
            $_SESSION['success_msg'] = 'Changes saved successfully';
            header("Location: members.php?do=edit&userid=$id");
            exit();
        }
    }

    // Insert Member Section
    elseif ($do == 'insert') {
        // ... [keep the existing insert logic, but translate error messages to English]
        // Example error message change:
        $errors[] = 'All fields are required';
        // ...
    }

    // Edit Member Section
    elseif ($do == 'edit') {
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        try {
            $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
            $stmt->execute([$userid]);
            $row = $stmt->fetch();

            if ($row) { ?>
                <div class="container py-5">
                    <?php if (isset($_SESSION['success_msg'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success_msg'] ?></div>
                        <?php unset($_SESSION['success_msg']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error_msg'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error_msg'] ?></div>
                        <?php unset($_SESSION['error_msg']); ?>
                    <?php endif; ?>

                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card shadow">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="mb-0">Edit Member</h3>
                                </div>
                                <div class="card-body">
                                    <form action="?do=update" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="userid" value="<?= $row['UserId'] ?>">

                                        <div class="mb-3 text-center">
                                            <?php if (!empty($row['avatar'])): ?>
                                                <img src="<?= $row['avatar'] ?>" class="img-thumbnail mb-3" style="max-width: 200px;">
                                            <?php endif; ?>
                                            <div class="input-group">
                                                <input type="file" name="avatar" class="form-control">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" name="fullname" class="form-control" 
                                                   value="<?= htmlspecialchars($row['FullName']) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" class="form-control" 
                                                   value="<?= htmlspecialchars($row['Username']) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" 
                                                   value="<?= htmlspecialchars($row['Email']) ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">New Password (leave blank to keep current)</label>
                                            <input type="password" name="newpassword" class="form-control">
                                            <input type="hidden" name="oldpassword" value="<?= $row['Password'] ?>">
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            } else {
                echo '<div class="alert alert-danger text-center">Member not found</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger text-center">Database Error: ' . $e->getMessage() . '</div>';
        }
    }

    // Delete Member Section
    elseif ($do == 'delete') {
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        // Delete user from database
        $stmt = $con->prepare("DELETE FROM users WHERE UserID = ?");
        $stmt->execute([$userid]);

        // Set success message
        $_SESSION['success_msg'] = 'Member deleted successfully';
        header('Location: members.php?do=manage');
        exit();
    }

    // Add Member Section
    elseif ($do == 'add') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullname = $_POST['fullname'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Validate user input
            $errors = array();
            if (empty($fullname)) {
                $errors[] = 'Full name is required';
            }
            if (empty($username)) {
                $errors[] = 'Username is required';
            }
            if (empty($email)) {
                $errors[] = 'Email is required';
            }
            if (empty($password)) {
                $errors[] = 'Password is required';
            }

            if (empty($errors)) {
                // تحقق مما إذا كان اسم المستخدم أو البريد الإلكتروني موجودًا مسبقًا
                $stmt = $con->prepare("SELECT COUNT(*) FROM users WHERE Username = ? OR Email = ?");
                $stmt->execute([$username, $email]);
                $count = $stmt->fetchColumn();
            
                if ($count > 0) {
                    echo '<div class="alert alert-danger">Username or email address is already in use. Please select other data.</div>';
                } else {
                    // إدراج عضو جديد في قاعدة البيانات
                    $stmt = $con->prepare("INSERT INTO users (FullName, Username, Email, Password) VALUES (?, ?, ?, ?)");
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt->execute([$fullname, $username, $email, $hashedPassword]);
            
                    // تعيين رسالة النجاح
                    $_SESSION['success_msg'] = 'تمت إضافة العضو بنجاح';
                    header("Location: members.php?do=manage");
                    exit();
                }
            } else {
                // عرض رسائل الخطأ
                foreach ($errors as $error) {
                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }
            }
        }                 

        // Display add member form
        ?>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0">Add Member</h3>
                        </div>
                        <div class="card-body">
                            <form action="?do=add" method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="fullname" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Add Member</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
include  $tpl. 'footer.php';
?>
