<?php
if (!isset($_SESSION['university_id'])) {
    header('Location: /gov/university-login');
    exit;
}
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Add Student Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Add New Student</h2>
        <form id="addStudentForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" name="first_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" name="last_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Gender</label>
                <select name="gender" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Department</label>
                <select name="department_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <!-- Populated via JavaScript -->
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Add Student</button>
        </form>
    </div>

    <!-- Add Department Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Add New Department</h2>
        <form id="addDepartmentForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Department Name</label>
                <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="3"></textarea>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Add Department</button>
        </form>
    </div>

    <!-- Add Course Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Add New Course</h2>
        <form id="addCourseForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Course Name</label>
                <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="3"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Department</label>
                <select name="department_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <!-- Populated via JavaScript -->
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Credits</label>
                <input type="number" name="credits" required min="1" max="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Add Course</button>
        </form>
    </div>
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

// Function to load departments
async function loadDepartments() {
    try {
        const response = await fetch('/gov/api/university/dashboard-stats.php');
        const data = await response.json();
        const departmentSelects = document.querySelectorAll('select[name="department_id"]');
        
        departmentSelects.forEach(select => {
            select.innerHTML = '<option value="">Select Department</option>';
            data.departments.forEach(dept => {
                const option = document.createElement('option');
                option.value = dept.id;
                option.textContent = dept.name;
                select.appendChild(option);
            });
        });
    } catch (error) {
        console.error('Error loading departments:', error);
    }
}

// Load departments when page loads
document.addEventListener('DOMContentLoaded', loadDepartments);

// Handle form submissions
const forms = {
    addStudentForm: '/gov/api/university/add-student.php',
    addDepartmentForm: '/gov/api/university/add-department.php',
    addCourseForm: '/gov/api/university/add-course.php'
};

Object.entries(forms).forEach(([formId, endpoint]) => {
    document.getElementById(formId).addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                showNotification(result.message);
                form.reset();
                // Reload departments if a new department was added
                if (formId === 'addDepartmentForm') {
                    loadDepartments();
                }
            } else {
                showNotification(result.error, 'error');
            }
        } catch (error) {
            showNotification('An error occurred. Please try again.', 'error');
        }
    });
});
</script>