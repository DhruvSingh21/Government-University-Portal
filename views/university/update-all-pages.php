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
    <title>Update Department References - EduGov Connect</title>
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
                    <img src="/gov/assets/images/logo.svg" alt="System Update" class="h-12 w-12 text-white">
                </div>
                <h2 class="text-white text-2xl font-semibold">System Diagnostics</h2>
                <p class="text-gray-400 text-sm">Check and update department references</p>
            </div>
            
            <div class="space-y-6 text-white">
                <div id="diagnostics-result" class="bg-gray-800 p-4 rounded-lg">
                    <p>Running diagnostics...</p>
                </div>
                
                <div class="space-y-4">
                    <h3 class="text-xl font-semibold">Department Configuration</h3>
                    <div id="departments-list" class="bg-gray-800 p-4 rounded-lg">
                        <p>Loading departments...</p>
                    </div>
                    
                    <button id="update-pages" class="w-full bg-green-500 text-white py-3 px-4 rounded-lg hover:bg-green-600 transition-colors">
                        Update All Pages
                    </button>
                    
                    <div id="update-results" class="bg-gray-800 p-4 rounded-lg hidden">
                        <p>Update results will appear here...</p>
                    </div>
                </div>
            </div>
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
    
    // Run diagnostics
    async function runDiagnostics() {
        try {
            const response = await fetch('/gov/api/diagnostics.php');
            const data = await response.json();
            
            document.getElementById('diagnostics-result').innerHTML = 
                `<pre class="text-xs overflow-auto max-h-40">${JSON.stringify(data, null, 2)}</pre>`;
            
            return data;
        } catch (error) {
            document.getElementById('diagnostics-result').innerHTML = 
                `<p class="text-red-500">Error running diagnostics: ${error.message}</p>`;
            return null;
        }
    }
    
    // Load departments
    async function loadDepartments() {
        try {
            const response = await fetch('/gov/api/university/get-departments.php');
            if (!response.ok) throw new Error(`HTTP error: ${response.status}`);
            
            const data = await response.json();
            const departments = data.departments || [];
            
            if (departments.length > 0) {
                let html = '<ul class="list-disc pl-5">';
                departments.forEach(dept => {
                    html += `<li>${dept.name} (ID: ${dept.id})</li>`;
                });
                html += '</ul>';
                document.getElementById('departments-list').innerHTML = html;
                
                // Store in localStorage as a backup
                localStorage.setItem('universityDepartments', JSON.stringify(departments));
            } else {
                document.getElementById('departments-list').innerHTML = 
                    `<p class="text-yellow-500">No departments found for your university.</p>`;
            }
        } catch (error) {
            document.getElementById('departments-list').innerHTML = 
                `<p class="text-red-500">Error loading departments: ${error.message}</p>`;
        }
    }
    
    // Update all pages
    document.getElementById('update-pages').addEventListener('click', async function() {
        const updateResults = document.getElementById('update-results');
        updateResults.classList.remove('hidden');
        updateResults.innerHTML = '<p>Updating site-wide department references...</p>';
        
        try {
            // Store the working API URL in localStorage
            localStorage.setItem('departmentsApiUrl', '/gov/api/university/get-departments.php');
            
            updateResults.innerHTML += `<p class="text-green-500">✓ Configuration updated.</p>`;
            updateResults.innerHTML += `<p>Please refresh any open pages that use departments.</p>`;
            
            showNotification('Update completed!');
        } catch (error) {
            updateResults.innerHTML += `<p class="text-red-500">Error: ${error.message}</p>`;
            showNotification('Update failed!', 'error');
        }
    });
    
    // Initialize
    document.addEventListener('DOMContentLoaded', async function() {
        await runDiagnostics();
        await loadDepartments();
    });
    </script>
</body>
</html>
