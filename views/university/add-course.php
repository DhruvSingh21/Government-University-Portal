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
    <title>Add Course - EduGov Connect</title>
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
            <img src="/gov/assets/images/logo.svg" alt="Add Course" class="h-12 w-12 text-white">
        </div>
        <h2 class="text-white text-2xl font-semibold">Add New Course</h2>
        <p class="text-gray-400 text-sm">Create a new course in your university</p>
    </div>
    <form id="addCourseForm" class="space-y-6" method="POST" action="/gov/api/university/add-course.php">
        <div class="grid md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-400 mb-2">Course Name</label>
                <input type="text" name="name" required class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500" placeholder="Enter course name">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Department</label>
                <select name="department_id" required class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500">
                    <!-- Populated via JavaScript -->
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Credits</label>
                <input type="number" name="credits" required min="1" max="6" class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500" placeholder="Enter credits (1-6)">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-400 mb-2">Duration</label>
                <input type="text" name="duration" class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-green-500" placeholder="e.g., 4 semesters, 2 years">
            </div>
        </div>
        <button type="submit" class="w-full bg-green-500 text-white py-3 px-4 rounded-lg hover:bg-green-600 transition-colors">Add Course</button>
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

// Function to load departments
async function loadDepartments() {
    try {
        console.log('Fetching departments from API...');
        
        // Direct fetch without retries for simplicity
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
        
        // Check if departments property exists and is not empty
        const departments = data.departments || [];
        
        if (departments && departments.length > 0) {
            populateDepartmentDropdown(departments);
            return; // Exit the function on success
        } else {
            throw new Error('No departments found');
        }
    } catch (error) {
        console.error('Error loading departments:', error);
        showNotification(`Failed to load departments: ${error.message}`, 'error');
        useFallbackDepartments();
    }
}

// Fallback function to populate departments when API fails
function useFallbackDepartments() {
    // Check if we have departments in localStorage first
    const localDepts = localStorage.getItem('universityDepartments');
    if (localDepts) {
        try {
            const departments = JSON.parse(localDepts);
            populateDepartmentDropdown(departments);
            return;
        } catch (e) {
            console.error('Error parsing local departments:', e);
        }
    }
    
    // Create a warning about using fallback data
    showNotification('Using fallback department data. Some functionality may be limited.', 'error');
    
    // Default fallback departments
    const fallbackDepartments = [
        { id: 'temp_1', name: 'Computer Science' },
        { id: 'temp_2', name: 'Engineering' },
        { id: 'temp_3', name: 'Business' },
        { id: 'temp_4', name: 'Arts & Humanities' },
        { id: 'temp_5', name: 'Medical Sciences' }
    ];
    
    populateDepartmentDropdown(fallbackDepartments);
}

// Helper to populate the dropdown
function populateDepartmentDropdown(departments) {
    const departmentSelect = document.querySelector('select[name="department_id"]');
    departmentSelect.innerHTML = '<option value="">Select Department</option>';
    
    departments
        .sort((a, b) => a.name.localeCompare(b.name))
        .forEach(dept => {
            const option = document.createElement('option');
            option.value = dept.id;
            option.textContent = dept.name;
            departmentSelect.appendChild(option);
        });
    
    console.log('Populated departments dropdown with', departments.length, 'departments');
}

// Load departments when page loads
document.addEventListener('DOMContentLoaded', loadDepartments);

// Handle form submission
document.getElementById('addCourseForm').addEventListener('submit', async (e) => {
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
        } else {
            showNotification(result.error, 'error');
        }
    } catch (error) {
        showNotification('An error occurred. Please try again.', 'error');
    }
});
</script>
    </div>
</body>
</html>