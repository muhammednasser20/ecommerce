// جلب جميع الحقول التي تحتوي على placeholder
const inputs = document.querySelectorAll('.form-control');

// إضافة الأحداث لكل حقل
inputs.forEach(input => {
    // عند التركيز (focus) داخل الحقل
    input.addEventListener('focus', () => {
        input.dataset.placeholder = input.placeholder; // تخزين نص placeholder مؤقتًا
        input.placeholder = ''; // إزالة نص placeholder
    });

    // عند الخروج من الحقل (blur)
    input.addEventListener('blur', () => {
        input.placeholder = input.dataset.placeholder; // إعادة نص placeholder
    });
});



document.addEventListener("DOMContentLoaded", function () {
    const toggleSidebar = document.getElementById("toggleSidebar");
    const sidebar = document.getElementById("sidebar");
    const userDropdown = document.getElementById("userDropdown");
    const userDropdownMenu = document.getElementById("userDropdownMenu");

    // Toggle Sidebar
    toggleSidebar.addEventListener("click", function () {
        sidebar.classList.toggle("hidden");
    });

    // Toggle User Dropdown Menu
    userDropdown.addEventListener("click", function (event) {
        event.preventDefault();
        userDropdownMenu.classList.toggle("active");
    });

    // Close Dropdown when clicking outside
    document.addEventListener("click", function (event) {
        if (!userDropdown.contains(event.target)) {
            userDropdownMenu.classList.remove("active");
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    // Confirm delete action
    document.querySelectorAll(".confirm-delete").forEach(button => {
        button.addEventListener("click", function (event) {
            if (!confirm("Are you sure you want to delete this member?")) {
                event.preventDefault();
            }
        });
    });

    // Search functionality
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("keyup", function () {
            let filter = searchInput.value.toLowerCase();
            let rows = document.querySelectorAll("tbody tr");
            
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    }
});

if (empty($errors)) {
    // تنفيذ العملية المطلوبة (إضافة - تعديل - حذف)
    if ($action == 'add') {
        $stmt = $con->prepare("INSERT INTO users (FullName, Username, Email, Password) VALUES (?, ?, ?, ?)");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute([$fullname, $username, $email, $hashedPassword]);
        $_SESSION['success_msg'] = 'تمت إضافة العضو بنجاح';
    } elseif ($action == 'delete') {
        $stmt = $con->prepare("DELETE FROM users WHERE UserID = ?");
        $stmt->execute([$userID]);
        $_SESSION['success_msg'] = 'تم حذف العضو بنجاح';
    } elseif ($action == 'edit') {
        $stmt = $con->prepare("UPDATE users SET FullName = ?, Email = ? WHERE UserID = ?");
        $stmt->execute([$fullname, $email, $userID]);
        $_SESSION['success_msg'] = 'تم تعديل بيانات العضو بنجاح';
    }

    // عرض الرسالة وإعادة التوجيه بعد 5 ثوانٍ
    echo '<div class="alert alert-success" id="msg">' . $_SESSION['success_msg'] . '</div>';
    echo '<script>
        setTimeout(function() {
            window.location.href = "members.php?do=manage";
        }, 5000);
    </script>';
    unset($_SESSION['success_msg']);
    exit();
} else {
    // عرض رسائل الخطأ مباشرة
    foreach ($errors as $error) {
        echo '<div class="alert alert-danger">' . $error . '</div>';
    }
}
