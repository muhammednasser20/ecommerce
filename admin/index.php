<?php 
    session_start(); /* ابدا جلسة جديدة */ 
    $noNavbar = ''; /* لا تستدعي الناف بار */ 
    $pageTitle = 'login'; /* سمي الصفحة تسجيل دخول */ 


    if(isset($_SESSION['Username']))    /* لما المستخدم يسجل دخول ابدا جلسة جديدة */ 
    { 
        header('Location: dashboard.php'); /* لما يسجل العميل حوله الي صفحة الداش بورد */ 
    }
        /* استدعي صفحة الاعدادت الخاصة بالموقع */ 
        include 'init.php';
        
    
    /* اعمل تشك شوف اليوزر جاي من قاعدة البيانات ولا لا */ 
if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $username = $_POST['user']; /* استدعي اسم المستخدم */
    $password = $_POST['pass']; /* استدعي كلمة المرور */  
    $hashedpass = sha1($password); /* استدعي الكلمات الخزنه */ 

   /* افحص اذا كان المستخدم موجود في قواعد البيانات او لا */ 

   $stmt = $con->prepare("SELECT UserID ,Username, Password FROM users WHERE Username = ? AND Password = ? AND GroupID=1 limit 1");
   $stmt->execute(array($username, $hashedpass));
   $row = $stmt->fetch(); /* اعمل لي عميلة جلب للبيانات */ 
   $count = $stmt->rowCount();

   //لو الكونت اكبر من صفر يبقى ادمن 
    if ($count > 0)
    {
        $_SESSION['Username'] = $username; // REG Session Name
        $_SESSION['ID'] = $row['UserID']; // REG Session ID
        header('location: dashboard.php'); //Redirect dashboard 
        exit();
    }



}

?>




<div class="page-login">
    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <h4>Admin Login</h4>
        <div class="login-icon">
            <i class="fas fa-user-shield"></i> <!-- أيقونة الحماية --> 
        </div>

        <input class="form-control input-lg" type="text" name="user" placeholder="Username" autocomplete="off" />
        <input class="form-control input-lg" type="password" name="pass" placeholder="Password" autocomplete="off" />
        <input class="btn btn-lg btn-primary btn-block" type="submit" value="Login" />
    </form>
</div>




<?php include  $tpl. 'footer.php'; ?>

