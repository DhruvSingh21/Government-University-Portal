<?php
session_start();
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
    <title>Add Student - EduGov Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto bg-gray-900 bg-opacity-50 p-8 rounded-xl mt-8">
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="/gov/assets/images/logo.svg" alt="Add Student" class="h-12 w-12 text-white">
                </div>
                <h2 class="text-white text-2xl font-semibold">Add New Student</h2>
                <p class="text-gray-400 text-sm">Enroll a new student in your university</p>
            </div>
            
            <form id="addStudentForm" class="space-y-6" method="POST" action="/gov/api/university/add-student.php">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">First Name</label>
                        <input type="text" name="first_name" required class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500" placeholder="Enter first name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Last Name</label>
                        <input type="text" name="last_name" required class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500" placeholder="Enter last name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500" placeholder="Enter email address">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Gender</label>
                        <select name="gender" required class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Department</label>
                        <select name="department_id" required class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500">
                            <option value="">Select Department</option>
                            <!-- Populated via JavaScript -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Course</label>
                        <select name="course_id" required class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500">
                            <option value="">Select Course</option>
                            <!-- Populated via JavaScript after department selection -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Enrollment Date</label>
                        <input type="date" name="enrollment_date" required class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500">
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-green-500 text-white py-3 px-4 rounded-lg hover:bg-green-600 transition-colors">Add Student</button>
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
            console.log('Fetching departments from API...');
            
            const response = await fetch('/gov/api/university/get-departments.php');
            console.log('API Response Status:', response.status);
            
            if (!response.ok) {
                throw new Error(`Failed to fetch departments. Status: ${response.status}`);
            }
            
            const responseText = await response.text();
            console.log('Raw API Response:', responseText);
            
            // Try to parse as JSON
            let data;
            try {
                data = JSON.parse(responseText);
                console.log('Parsed API Response:', data);
            } catch (parseError) {
                console.error('JSON Parse Error:', parseError);
                throw new Error('Invalid JSON response from server');
            }
            
            const departmentSelect = document.querySelector('select[name="department_id"]');
            departmentSelect.innerHTML = '<option value="">Select Department</option>';
            
            // Check if departments property exists and is not empty
            const departments = data.departments || [];
            
            if (departments && departments.length > 0) {
                departments
                    .sort((a, b) => a.name.localeCompare(b.name))
                    .forEach(dept => {
                        const option = document.createElement('option');
                        option.value = dept.id;
                        option.textContent = dept.name;
                        departmentSelect.appendChild(option);
                    });
                console.log('Successfully populated departments dropdown with', departments.length, 'departments');
            } else {
                console.log('No departments found in data structure:', data);
                showNotification('No departments found. Please add departments first.', 'error');
                setTimeout(() => {
                    window.location.href = '/gov/views/university/add-department.php';
                }, 2000);
            }
        } catch (error) {
            console.error('Error loading departments:', error);
            showNotification(`Failed to load departments: ${error.message}`, 'error');
        }
    }

    // Function to load courses based on selected department
    async function loadCourses(departmentId) {
        try {
            // Clear the courses dropdown
            const courseSelect = document.querySelector('select[name="course_id"]');
            courseSelect.innerHTML = '<option value="">Select Course</option>';
            
            if (!departmentId) {
                console.log("No department ID provided");
                return;
            }
            
            console.log("Fetching courses for department ID:", departmentId);
            
            // Use the new, dedicated endpoint
            const response = await fetch(`/gov/api/university/list-courses.php?department_id=${departmentId}`);
            
            if (!response.ok) {
                throw new Error(`Failed to fetch courses. Status: ${response.status}`);
            }
            
            const result = await response.json();
            console.log("Courses API response:", result);
            
            if (result.courses && result.courses.length > 0) {
                result.courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = course.name;
                    courseSelect.appendChild(option);
                });
                console.log(`Added ${result.courses.length} courses to dropdown`);
            } else {
                console.warn("No courses found for department:", departmentId);
                showNotification('No courses found for this department. Please add courses first.', 'error');
                setTimeout(() => {
                    window.location.href = '/gov/views/university/add-course.php';
                }, 2000);
            }
        } catch (error) {
            console.error("Error loading courses:", error);
            showNotification(`Failed to load courses: ${error.message}`, 'error');
        }
    }

    // Set today's date as the default enrollment date and setup event listeners
    document.addEventListener('DOMContentLoaded', () => {
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="enrollment_date"]').value = today;
        
        loadDepartments();
        
        // Add event listener for department selection to load courses
        document.querySelector('select[name="department_id"]').addEventListener('change', (e) => {
            const departmentId = e.target.value;
            if (departmentId) {
                loadCourses(departmentId);
            } else {
                // Reset the course dropdown if no department is selected
                document.querySelector('select[name="course_id"]').innerHTML = '<option value="">Select Course</option>';
            }
        });
    });

    // Handle form submission
    document.getElementById('addStudentForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);

        try {
            const response = await fetch(form.action, {
                method: form.method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                showNotification(result.message);
                form.reset();
                
                // Reset the default enrollment date to today
                const today = new Date().toISOString().split('T')[0];
                document.querySelector('input[name="enrollment_date"]').value = today;
            } else {
                showNotification(result.error, 'error');
            }
        } catch (error) {
            showNotification('An error occurred. Please try again.', 'error');
        }
    });
    </script>
</body>
</html>