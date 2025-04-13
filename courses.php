<?php
session_start();
include_once 'includes/config.php';

// Verify admin login and role

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Courses Management - EduGov Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .animate-float { animation: float 6s ease-in-out infinite; }

        .stats-card {
            @apply bg-white/5 backdrop-blur-sm hover:bg-white/10 transition-all;
        }

        .data-table th {
            @apply px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider;
        }

        .data-table td {
            @apply px-6 py-4 whitespace-nowrap text-sm text-gray-200;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-blue-900 to-purple-900 overflow-hidden">
    <!-- Background Animation -->
    <div class="fixed inset-0 opacity-10 pointer-events-none">
        <div class="animate-float absolute w-64 h-64 bg-gradient-to-r from-purple-400/20 to-blue-500/20 rounded-full blur-3xl -top-32 -left-32"></div>
        <div class="animate-float absolute w-96 h-96 bg-gradient-to-r from-blue-500/20 to-indigo-400/20 rounded-full blur-3xl -bottom-48 -right-48" style="animation-delay: -3s"></div>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-gray-900/80 text-white border-r border-gray-700/50 flex-shrink-0">
            <div class="p-6 border-b border-gray-700/50">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-university text-purple-400"></i>
                    </div>
                    <h1 class="text-xl font-bold">EduGov Connect</h1>
                </div>
                <p class="text-gray-400 text-sm mt-1">Government of India</p>
                <p class="text-gray-400 text-sm">Mumbai University</p>
            </div>
            <nav class="p-4">
                <ul class="space-y-2">
                    <li><a href="dashboard.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-tachometer-alt w-5"></i><span>Dashboard</span></a></li>
                    <li><a href="student.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-users w-5"></i><span>Students</span></a></li>
                    <li><a href="departments.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-building w-5"></i><span>Departments</span></a></li>
                    <li><a href="courses.php" class="flex items-center space-x-2 px-3 py-2 bg-white/5 rounded-lg text-purple-400"><i class="fas fa-book-open w-5"></i><span>Courses</span></a></li>
                    <li><a href="settings.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-cog w-5"></i><span>Settings</span></a></li>
                    <li><a href="logout.php" class="flex items-center space-x-2 px-3 py-2 hover:bg-white/5 rounded-lg"><i class="fas fa-sign-out-alt w-5"></i><span>Logout</span></a></li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <header class="bg-gray-900/80 border-b border-gray-700/50 px-8 py-4 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-white">Courses Management</h1>
                    <p class="text-gray-400">Manage all academic courses of Mumbai University</p>
                </div>
                <div class="flex items-center gap-4">
                    <button class="p-2 hover:bg-white/5 rounded-full"><i class="fas fa-bell text-gray-400"></i></button>
                    <div class="w-10 h-10 bg-purple-500/20 rounded-full flex items-center justify-center"><i class="fas fa-user text-purple-400"></i></div>
                </div>
            </header>

            <main class="p-8 space-y-8">
                <!-- Course Statistics -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="stats-card p-6 rounded-xl border border-gray-700/50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400">Total Courses</p>
                                <p class="text-3xl text-white font-bold">86</p>
                            </div>
                            <i class="fas fa-book-open text-3xl text-purple-400"></i>
                        </div>
                    </div>
                    <div class="stats-card p-6 rounded-xl border border-gray-700/50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400">Undergraduate</p>
                                <p class="text-3xl text-white font-bold">54</p>
                            </div>
                            <i class="fas fa-graduation-cap text-3xl text-purple-400"></i>
                        </div>
                    </div>
                    <div class="stats-card p-6 rounded-xl border border-gray-700/50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400">Postgraduate</p>
                                <p class="text-3xl text-white font-bold">28</p>
                            </div>
                            <i class="fas fa-user-graduate text-3xl text-purple-400"></i>
                        </div>
                    </div>
                    <div class="stats-card p-6 rounded-xl border border-gray-700/50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-400">Doctoral</p>
                                <p class="text-3xl text-white font-bold">4</p>
                            </div>
                            <i class="fas fa-microscope text-3xl text-purple-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Courses Table with CRUD Operations -->
                <div class="bg-gray-900/80 rounded-xl border border-gray-700/50 overflow-hidden">
                    <div class="p-6 border-b border-gray-700/50 flex justify-between items-center">
                        <h2 class="text-xl font-bold text-white">All Courses</h2>
                        <button onclick="openAddCourseModal()" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-white transition-colors">
                            <i class="fas fa-plus mr-2"></i> Add Course
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full data-table">
                            <thead class="bg-gray-800">
                                <tr>
                                    <th class="pl-6">Course Code</th>
                                    <th>Course Name</th>
                                    <th>Department</th>
                                    <th>Level</th>
                                    <th>Duration</th>
                                    <th>Credits</th>
                                    <th class="pr-6">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                        
                               
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Course Details Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Popular Courses -->
                    <div class="lg:col-span-2 bg-gray-900/80 p-6 rounded-xl border border-gray-700/50">
                        <h2 class="text-xl font-bold text-white mb-4">Most Popular Courses</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-800/50 p-4 rounded-lg">
                                <h3 class="text-purple-400 mb-2">By Enrollment</h3>
                                <ol class="list-decimal list-inside space-y-1">
                                    <li>B.Tech Computer Science (1,242 students)</li>
                                    <li>BBA (1,087 students)</li>
                                    <li>B.Com (876 students)</li>
                                    <li>BA English (754 students)</li>
                                    <li>B.Sc Physics (612 students)</li>
                                </ol>
                            </div>
                            <div class="bg-gray-800/50 p-4 rounded-lg">
                                <h3 class="text-purple-400 mb-2">Newly Added</h3>
                                <ul class="space-y-2">
                                    <li class="flex items-center">
                                        <div class="w-8 h-8 bg-purple-500/20 rounded-full flex items-center justify-center mr-2">
                                            <i class="fas fa-book text-xs text-purple-400"></i>
                                        </div>
                                        M.Sc Artificial Intelligence
                                    </li>
                                    <li class="flex items-center">
                                        <div class="w-8 h-8 bg-purple-500/20 rounded-full flex items-center justify-center mr-2">
                                            <i class="fas fa-book text-xs text-purple-400"></i>
                                        </div>
                                        B.Tech Cyber Security
                                    </li>
                                    <li class="flex items-center">
                                        <div class="w-8 h-8 bg-purple-500/20 rounded-full flex items-center justify-center mr-2">
                                            <i class="fas fa-book text-xs text-purple-400"></i>
                                        </div>
                                        MBA in Digital Marketing
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-900/80 p-6 rounded-xl border border-gray-700/50">
                        <h2 class="text-xl font-bold text-white mb-4">Quick Actions</h2>
                        <div class="space-y-3">
                            <button onclick="openAddCourseModal()" class="w-full flex items-center space-x-3 px-4 py-3 bg-purple-600/30 hover:bg-purple-600/40 rounded-lg transition-colors">
                                <i class="fas fa-plus-circle text-purple-400"></i>
                                <span>Create New Course</span>
                            </button>
                            <button class="w-full flex items-center space-x-3 px-4 py-3 bg-blue-600/30 hover:bg-blue-600/40 rounded-lg transition-colors">
                                <i class="fas fa-file-export text-blue-400"></i>
                                <span>Export Course Catalog</span>
                            </button>
                            <button class="w-full flex items-center space-x-3 px-4 py-3 bg-green-600/30 hover:bg-green-600/40 rounded-lg transition-colors">
                                <i class="fas fa-file-import text-green-400"></i>
                                <span>Import Course Syllabus</span>
                            </button>
                            <button class="w-full flex items-center space-x-3 px-4 py-3 bg-yellow-600/30 hover:bg-yellow-600/40 rounded-lg transition-colors">
                                <i class="fas fa-chart-pie text-yellow-400"></i>
                                <span>Generate Enrollment Reports</span>
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add/Edit Course Modal -->
    <div id="courseModal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-gray-800 rounded-xl w-full max-w-2xl border border-gray-700/50">
            <div class="p-6 border-b border-gray-700/50 flex justify-between items-center">
                <h3 id="modalCourseTitle" class="text-xl font-bold text-white">Add New Course</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="courseForm" action="includes/save_course.php" method="POST" class="p-6 space-y-4">
                <input type="hidden" id="courseId" name="id" value="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="courseCode" class="block text-gray-300 mb-1">Course Code*</label>
                        <input type="text" id="courseCode" name="code" required class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    </div>
                    <div>
                        <label for="courseName" class="block text-gray-300 mb-1">Course Name*</label>
                        <input type="text" id="courseName" name="name" required class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    </div>
                    <div>
                        <label for="courseDept" class="block text-gray-300 mb-1">Department*</label>
                        <select id="courseDept" name="department" required class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['department_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="courseLevel" class="block text-gray-300 mb-1">Level*</label>
                        <select id="courseLevel" name="level" required class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                            <option value="UG">Undergraduate</option>
                            <option value="PG">Postgraduate</option>
                            <option value="DR">Doctoral</option>
                        </select>
                    </div>
                    <div>
                        <label for="courseDuration" class="block text-gray-300 mb-1">Duration (years)*</label>
                        <input type="number" id="courseDuration" name="duration" min="1" max="6" required class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    </div>
                    <div>
                        <label for="courseCredits" class="block text-gray-300 mb-1">Total Credits*</label>
                        <input type="number" id="courseCredits" name="credits" min="1" max="200" required class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white">
                    </div>
                </div>
                <div>
                    <label for="courseDesc" class="block text-gray-300 mb-1">Description</label>
                    <textarea id="courseDesc" name="description" rows="3" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white"></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-lg text-white transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-white transition-colors">
                        Save Course
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-gray-800 rounded-xl w-full max-w-md border border-gray-700/50">
            <div class="p-6 border-b border-gray-700/50">
                <h3 class="text-xl font-bold text-white">Confirm Deletion</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-300 mb-4">Are you sure you want to delete <span id="courseToDelete" class="font-semibold text-white"></span> course? This action cannot be undone.</p>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-lg text-white transition-colors">
                        Cancel
                    </button>
                    <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white transition-colors">
                        Delete Course
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Course Modal Functions
        function openAddCourseModal() {
            document.getElementById('modalCourseTitle').textContent = 'Add New Course';
            document.getElementById('courseId').value = '';
            document.getElementById('courseCode').value = '';
            document.getElementById('courseName').value = '';
            document.getElementById('courseDept').value = '';
            document.getElementById('courseLevel').value = 'UG';
            document.getElementById('courseDuration').value = '3';
            document.getElementById('courseCredits').value = '120';
            document.getElementById('courseDesc').value = '';
            document.getElementById('courseModal').classList.remove('hidden');
        }

        function openEditCourseModal(id, code, name, dept, level, duration, credits, desc) {
            document.getElementById('modalCourseTitle').textContent = 'Edit Course';
            document.getElementById('courseId').value = id;
            document.getElementById('courseCode').value = code;
            document.getElementById('courseName').value = name;
            document.getElementById('courseDept').value = dept;
            document.getElementById('courseLevel').value = level;
            document.getElementById('courseDuration').value = duration;
            document.getElementById('courseCredits').value = credits;
            document.getElementById('courseDesc').value = desc;
            document.getElementById('courseModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('courseModal').classList.add('hidden');
        }

        // Delete Modal Functions
        let courseToDeleteId = null;

        function confirmDeleteCourse(id, name) {
            courseToDeleteId = id;
            document.getElementById('courseToDelete').textContent = name;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            courseToDeleteId = null;
            document.getElementById('deleteModal').classList.add('hidden');
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (courseToDeleteId) {
                window.location.href = `includes/delete_course.php?id=${courseToDeleteId}`;
            }
        });

        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('courseModal')) {
                closeModal();
            }
            if (event.target === document.getElementById('deleteModal')) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>