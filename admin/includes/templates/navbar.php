<?php

if (isset($_SESSION['Username'])) { // التحقق مما إذا كان اسم المستخدم موجودًا
    // الاتصال بقاعدة البيانات
    include 'connect.php'; // تأكد من أن ملف الاتصال بقاعدة البيانات موجود

    // جلب اسم المستخدم من قاعدة البيانات
    $stmt = $con->prepare("SELECT FullName FROM users WHERE Username = ?");
    $stmt->execute(array($_SESSION['Username']));
    $row = $stmt->fetch();
    $fullName = $row['FullName'] ?? 'Guest'; // حفظ اسم المستخدم الكامل، مع قيمة افتراضية إذا لم يوجد

    ?>

    <!--============= بداية الكود HTML ==================== -->

    <!-- Navbar -->
    <nav class="navbar">
        <div class="d-flex align-items-center">
            <!-- Sidebar Toggle Button -->
            <button id="toggleSidebar" class="btn btn-light d-md-none me-3">
                <i class="fas fa-bars fa-5x"></i>
            </button>
            <!-- Logo -->
            <a class="navbar-brand" href="#">
                <img src="../../../../ecommerce/admin/uploads/logo.png" alt="Brand Logo">
            </a>
        </div>

        <!-- User Dropdown -->
        <div class="navbar-user">
            <a class="nav-link" href="#" id="userDropdown">
                <img src="../../../../ecommerce/admin/uploads/avatar.png" alt="User Avatar">
                <?php echo htmlspecialchars($fullName); // عرض اسم المستخدم ?>
                <i class="fas fa-chevron-down"></i>
            </a>
            <div class="dropdown-menu" id="userDropdownMenu">
                <!-- جلب الصفحة باستخدام الرابط -->
                <a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo isset($_SESSION['ID']) ? $_SESSION['ID'] : 0; ?>">
                    <?php echo lang('profile user editing'); ?>
                </a>
                <a class="dropdown-item" href="#"><?php echo lang('profile user Settings'); ?></a>
                <a class="dropdown-item" href="logout.php"><?php echo lang('profile user logout'); ?></a>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <img src="../../../../ecommerce/admin/uploads/avatar.png" alt="User Avatar">
        <p><?php echo htmlspecialchars($fullName); ?></p>
    </div>

    <!-- لاحظ إضافة الصنف sidebar-link -->
    <a href="dashboard.php" class="sidebar-item sidebar-link active">
        <i class="fas fa-home"></i> <?php echo lang('side menu home'); ?>
    </a>
    <a href="#" class="sidebar-item sidebar-link">
        <i class="fas fa-layer-group"></i> <?php echo lang('side menu Categories'); ?>
    </a>
    <a href="#" class="sidebar-item sidebar-link">
        <i class="fas fa-list-ul"></i> <?php echo lang('side menu Items'); ?>
    </a>
    <a href="members.php" class="sidebar-item sidebar-link">
        <i class="fas fa-user"></i> <?php echo lang('side menu Members'); ?>
    </a>
    <a href="#" class="sidebar-item sidebar-link">
        <i class="fas fa-chart-line"></i> <?php echo lang('side menu Statics'); ?>
    </a>
    <a href="#" class="sidebar-item sidebar-link">
        <i class="fas fa-anchor"></i> <?php echo lang('side menu file'); ?>
    </a>
</div>

    <?php
    include $tpl . 'footer.php'; // إدراج ملف الفوتر
} else {
    header('Location: index.php'); // إعادة التوجيه إلى صفحة تسجيل الدخول
    exit();
}

?>
