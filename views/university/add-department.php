<?php
session_start();
ob_start();
if (!isset($_SESSION['university_id'])) {
    header('Location: /gov/views/university-login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Department - EduGov Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto bg-gray-900 bg-opacity-50 p-8 rounded-xl mt-8">
    <div class="text-center mb-8">
        <div class="flex justify-center mb-4">
            <img src="/gov/assets/images/logo.svg" alt="Add Department" class="h-12 w-12 text-white">
        </div>
        <h2 class="text-white text-2xl font-semibold">Add New Department</h2>
        <p class="text-gray-400 text-sm">Create a new department in your university</p>
    </div>
    <form id="addDepartmentForm" class="space-y-6" method="POST" action="/gov/api/university/add-department.php">
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Department Name</label>
            <input type="text" name="name" required class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500" placeholder="Enter department name">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Description</label>
            <textarea name="description" required class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500" rows="3" placeholder="Enter department description"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Head of Department Name</label>
            <input type="text" name="hod_name" class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500" placeholder="Enter HOD name">
        </div>
        <button type="submit" class="w-full bg-green-500 text-white py-3 px-4 rounded-lg hover:bg-green-600 transition-colors">Add Department</button>
    </form>
</div>

<script>
// Function to show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 p-4 rounded-md ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

// Handle form submission
document.getElementById('addDepartmentForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    try {
        const formDataObject = Object.fromEntries(formData);
        const response = await fetch(form.action, {
            method: form.method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formDataObject)
        });

        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.error || 'Network response was not ok');
        }

        showNotification(result.message);
        form.reset();
    } catch (error) {
        showNotification(error.message || 'An error occurred. Please try again.', 'error');
        console.error('Error:', error);
    }
});
</script>
    </div>
</body>
</html>
<?php
ob_end_flush();
?>