<?php
require_once '../config.php';

// Check if we have an action parameter
$action = $_GET['action'] ?? 'check';

// Function to check database structure
function checkDatabaseStructure($db) {
    $issues = [];
    
    // Check if users table has university_id column
    try {
        $stmt = $db->prepare("SHOW COLUMNS FROM users LIKE 'university_id'");
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            $issues[] = "Missing 'university_id' column in users table";
        }
    } catch (Exception $e) {
        $issues[] = "Error checking users table: " . $e->getMessage();
    }
    
    // Check if government_officials table exists
    try {
        $stmt = $db->prepare("SHOW TABLES LIKE 'government_officials'");
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            $issues[] = "Missing 'government_officials' table";
        }
    } catch (Exception $e) {
        $issues[] = "Error checking government_officials table: " . $e->getMessage();
    }
    
    return $issues;
}

// Function to fix database structure
function fixDatabaseStructure($db) {
    $results = [];
    
    // Add university_id column to users table if missing
    try {
        $stmt = $db->prepare("SHOW COLUMNS FROM users LIKE 'university_id'");
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            $db->exec("ALTER TABLE users ADD COLUMN university_id INT NULL");
            $results[] = "Added 'university_id' column to users table";
        } else {
            $results[] = "'university_id' column already exists in users table";
        }
    } catch (Exception $e) {
        $results[] = "Error adding university_id column: " . $e->getMessage();
    }
    
    // Create government_officials table if missing
    try {
        $stmt = $db->prepare("SHOW TABLES LIKE 'government_officials'");
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            $db->exec("
                CREATE TABLE government_officials (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    user_id INT NOT NULL,
                    first_name VARCHAR(100) NOT NULL,
                    last_name VARCHAR(100) NOT NULL,
                    department VARCHAR(100) NOT NULL,
                    designation VARCHAR(100) NOT NULL,
                    employee_id VARCHAR(50) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id)
                )
            ");
            $results[] = "Created 'government_officials' table";
        } else {
            $results[] = "'government_officials' table already exists";
        }
    } catch (Exception $e) {
        $results[] = "Error creating government_officials table: " . $e->getMessage();
    }
    
    return $results;
}

// Handle actions
if ($action === 'fix') {
    $results = fixDatabaseStructure($db);
    $title = "Database Update Results";
    $buttonText = "Check Structure";
    $buttonAction = "check";
} else {
    $issues = checkDatabaseStructure($db);
    $title = "Database Structure Check";
    $buttonText = "Fix Issues";
    $buttonAction = "fix";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Schema Update - EduGov Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="container max-w-2xl mx-auto bg-gray-900 bg-opacity-50 p-8 rounded-xl text-white">
        <h1 class="text-2xl font-bold text-center mb-6"><?php echo $title; ?></h1>
        
        <?php if ($action === 'check'): ?>
            <?php if (empty($issues)): ?>
                <div class="bg-green-600 bg-opacity-50 p-4 rounded-md mb-6">
                    <p class="text-center">✓ Database structure looks good! No issues found.</p>
                </div>
            <?php else: ?>
                <div class="bg-yellow-600 bg-opacity-50 p-4 rounded-md mb-6">
                    <p class="font-medium mb-2">The following issues were found:</p>
                    <ul class="list-disc list-inside">
                        <?php foreach ($issues as $issue): ?>
                            <li><?php echo htmlspecialchars($issue); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="bg-blue-600 bg-opacity-50 p-4 rounded-md mb-6">
                <p class="font-medium mb-2">Update results:</p>
                <ul class="list-disc list-inside">
                    <?php foreach ($results as $result): ?>
                        <li><?php echo htmlspecialchars($result); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="flex justify-center mt-6">
            <?php if ($action === 'check' && !empty($issues)): ?>
                <a href="?action=fix" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg transition-colors">
                    <?php echo $buttonText; ?>
                </a>
            <?php elseif ($action === 'fix'): ?>
                <a href="?action=check" class="bg-green-600 hover:bg-green-700 text-white py-2 px-6 rounded-lg transition-colors">
                    <?php echo $buttonText; ?>
                </a>
            <?php endif; ?>
            
            <a href="/gov/views/home.php" class="ml-4 bg-gray-600 hover:bg-gray-700 text-white py-2 px-6 rounded-lg transition-colors">
                Return to Home
            </a>
        </div>
    </div>
</body>
</html>
