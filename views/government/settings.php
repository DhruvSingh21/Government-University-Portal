<?php
session_start();
require_once '../../config.php';

// Check if user is logged in as a government official
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'government_official') {
    header('Location: /gov/views/government-login.php');
    exit;
}

$success_message = '';
$error_message = '';

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    try {
        $stmt = $db->prepare("
            UPDATE government_officials 
            SET first_name = ?, last_name = ?, department = ?, designation = ? 
            WHERE user_id = ?
        ");
        
        $result = $stmt->execute([
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['department'],
            $_POST['designation'],
            $_SESSION['user_id']
        ]);
        
        if ($result) {
            // Update session variables
            $_SESSION['first_name'] = $_POST['first_name'];
            $_SESSION['last_name'] = $_POST['last_name'];
            $_SESSION['department'] = $_POST['department'];
            $_SESSION['designation'] = $_POST['designation'];
            
            $success_message = 'Profile updated successfully';
        } else {
            $error_message = 'Failed to update profile';
        }
    } catch (Exception $e) {
        $error_message = 'Error: ' . $e->getMessage();
    }
}

// Handle form submission for password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password !== $confirm_password) {
        $error_message = 'New passwords do not match';
    } else {
        try {
            // Verify current password
            $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!password_verify($current_password, $user['password'])) {
                $error_message = 'Current password is incorrect';
            } else {
                // Update password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $result = $stmt->execute([$hashed_password, $_SESSION['user_id']]);
                
                if ($result) {
                    $success_message = 'Password changed successfully';
                } else {
                    $error_message = 'Failed to change password';
                }
            }
        } catch (Exception $e) {
            $error_message = 'Error: ' . $e->getMessage();
        }
    }
}

// Get current user data
try {
    $stmt = $db->prepare("
        SELECT g.*, u.email 
        FROM government_officials g 
        JOIN users u ON g.user_id = u.id 
        WHERE g.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error_message = 'Error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - EduGov Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #1e3a8a;
        }
        .section-card {
            background-color: #192656;
            border-radius: 0.5rem;
            padding: 1.5rem;
        }
    </style>
</head>
<body class="text-white">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 p-6 flex flex-col bg-gray-900">
            <div class="mb-10">
                <a href="/gov" class="flex items-center">
                    <span class="text-xl font-bold">EduGov Connect</span>
                </a>
            </div>
            
            <nav class="flex-1">
                <a href="/gov/views/government/dashboard.php" class="flex items-center py-3 px-4 hover:bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">☰</span>
                    <span>Dashboard</span>
                </a>
                
                <a href="/gov/views/government/universities.php" class="flex items-center py-3 px-4 hover:bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">🏛️</span>
                    <span>Universities</span>
                </a>
                
                <a href="/gov/views/government/reports.php" class="flex items-center py-3 px-4 hover:bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">📊</span>
                    <span>Reports</span>
                </a>
                
                <a href="/gov/views/government/settings.php" class="flex items-center py-3 px-4 bg-blue-800 rounded-md mb-2">
                    <span class="mr-2">⚙️</span>
                    <span>Settings</span>
                </a>
                
                <a href="/gov/controllers/auth/logout.php" class="flex items-center py-3 px-4 hover:bg-red-800 rounded-md mt-auto">
                    <span class="mr-2">🚪</span>
                    <span>Logout</span>
                </a>
            </nav>
        </div>
        
        <!-- Main content -->
        <div class="flex-1 p-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold">Settings</h1>
                <p class="text-gray-400">Manage your account preferences</p>
            </div>
            
            <?php if ($success_message): ?>
                <div class="bg-green-500 text-white p-4 rounded-md mb-6">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="bg-red-500 text-white p-4 rounded-md mb-6">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <!-- Settings Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Profile Settings -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-4">Profile Settings</h2>
                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-gray-400 mb-1">Email</label>
                            <input type="email" value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" disabled class="w-full px-3 py-2 bg-gray-700 rounded-md">
                            <p class="text-xs text-gray-500 mt-1">Email cannot be changed</p>
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-1">First Name</label>
                            <input type="text" name="first_name" value="<?php echo htmlspecialchars($user_data['first_name'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-md">
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-1">Last Name</label>
                            <input type="text" name="last_name" value="<?php echo htmlspecialchars($user_data['last_name'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-md">
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-1">Department</label>
                            <input type="text" name="department" value="<?php echo htmlspecialchars($user_data['department'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-md">
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-1">Designation</label>
                            <input type="text" name="designation" value="<?php echo htmlspecialchars($user_data['designation'] ?? ''); ?>" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-md">
                        </div>
                        <button type="submit" name="update_profile" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update Profile
                        </button>
                    </form>
                </div>
                
                <!-- Password Settings -->
                <div class="section-card">
                    <h2 class="text-xl font-bold mb-4">Change Password</h2>
                    <form method="POST" class="space-y-4">
                        <div>
                            <label class="block text-gray-400 mb-1">Current Password</label>
                            <input type="password" name="current_password" required class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-md">
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-1">New Password</label>
                            <input type="password" name="new_password" required class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-md">
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-1">Confirm New Password</label>
                            <input type="password" name="confirm_password" required class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-md">
                        </div>
                        <button type="submit" name="change_password" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
