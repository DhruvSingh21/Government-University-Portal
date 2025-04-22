<?php
class Auth {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Register a government official
     */
    public function registerGovernment($data) {
        // Validate email format - Updated to match exactly @gov.in at the end
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || !preg_match('/@gov\.in$/', $data['email'])) {
            throw new Exception('Invalid government email format');
        }
        
        // Check if email already exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$data['email']]);
        
        if ($stmt->rowCount() > 0) {
            throw new Exception('Email already registered');
        }
        
        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Insert into users table
        $stmt = $this->db->prepare("
            INSERT INTO users (email, password, role) 
            VALUES (?, ?, 'government_official')
        ");
        
        $result = $stmt->execute([$data['email'], $hashedPassword]);
        
        if ($result) {
            $userId = $this->db->lastInsertId();
            
            // Create a government_officials table entry if it doesn't exist
            $this->createGovernmentOfficialTable();
            
            // Insert official details
            $stmt = $this->db->prepare("
                INSERT INTO government_officials (
                    user_id, first_name, last_name, department, 
                    designation, employee_id
                ) VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            return $stmt->execute([
                $userId,
                $data['first_name'],
                $data['last_name'],
                $data['department'],
                $data['designation'],
                $data['employee_id']
            ]);
        }
        
        return false;
    }
    
    /**
     * Login a user (works for both government officials and university admins)
     */
    public function login($email, $password, $role = null) {
        try {
            // Query to get user by email
            $stmt = $this->db->prepare("SELECT id, password, role FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() == 0) {
                return false; // User not found
            }
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password
            if (!password_verify($password, $user['password'])) {
                return false; // Wrong password
            }
            
            // Check role if specified
            if ($role !== null && $user['role'] !== $role) {
                return false; // Wrong role
            }
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $user['role'];
            
            // Load additional details based on role
            if ($user['role'] == 'government_official') {
                $this->loadGovernmentOfficialDetails($user['id']);
            } else if ($user['role'] == 'university_admin') {
                $this->loadUniversityDetails($user['id']);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Login specifically for government officials
     */
    public function governmentLogin($email, $password) {
        return $this->login($email, $password, 'government_official');
    }
    
    /**
     * Login specifically for universities
     */
    public function universityLogin($email, $password) {
        try {
            // Check if this email exists in users table
            $stmt = $this->db->prepare("SELECT id, password, role FROM users WHERE email = ? AND role = 'university_admin'");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() == 0) {
                return false; // User not found or not university admin
            }
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify password
            if (!password_verify($password, $user['password'])) {
                return false; // Wrong password
            }
            
            // Now get the university ID for this user/email
            $stmt = $this->db->prepare("
                SELECT u.id, u.name, u.email 
                FROM universities u 
                INNER JOIN users us ON u.email = us.email 
                WHERE us.id = ?
            ");
            $stmt->execute([$user['id']]);
            
            if ($stmt->rowCount() == 0) {
                // Try the university_id field directly if the join approach fails
                $stmt = $this->db->prepare("SELECT university_id FROM users WHERE id = ?");
                $stmt->execute([$user['id']]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$result || !$result['university_id']) {
                    error_log("No university found for user ID: {$user['id']}, email: $email");
                    return false;
                }
                
                $universityId = $result['university_id'];
                
                // Now get the university details
                $stmt = $this->db->prepare("SELECT id, name, email FROM universities WHERE id = ?");
                $stmt->execute([$universityId]);
                
                if ($stmt->rowCount() == 0) {
                    error_log("No university found with ID: $universityId");
                    return false;
                }
                
                $university = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $university = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'university_admin';
            $_SESSION['university_id'] = $university['id'];
            $_SESSION['university_name'] = $university['name'];
            $_SESSION['university_email'] = $university['email'];
            
            error_log("University login successful: {$university['name']} (ID: {$university['id']})");
            return true;
            
        } catch (Exception $e) {
            error_log("University login error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if current user is a government official
     */
    public function isGovernmentOfficial() {
        return isset($_SESSION['role']) && $_SESSION['role'] == 'government_official';
    }
    
    /**
     * Load government official details into session
     */
    private function loadGovernmentOfficialDetails($userId) {
        // Create table if it doesn't exist
        $this->createGovernmentOfficialTable();
        
        $stmt = $this->db->prepare("
            SELECT first_name, last_name, department, designation, employee_id
            FROM government_officials WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        
        if ($stmt->rowCount() > 0) {
            $official = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['first_name'] = $official['first_name'];
            $_SESSION['last_name'] = $official['last_name'];
            $_SESSION['department'] = $official['department'];
            $_SESSION['designation'] = $official['designation'];
        }
    }
    
    /**
     * Load university details into session - Completely replaced with direct approach in universityLogin
     */
    private function loadUniversityDetails($userId) {
        // This method isn't used directly anymore
        // The universityLogin method handles setting session variables directly
    }
    
    /**
     * Create government_officials table if it doesn't exist
     */
    private function createGovernmentOfficialTable() {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS government_officials (
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
    }

    /**
     * Register a university and create admin user
     */
    public function registerUniversity($data) {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // Insert into universities table
            $stmt = $this->db->prepare("INSERT INTO universities (name, email, address, phone) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $data['university_name'],
                $data['email'],
                $data['address'],
                $data['phone']
            ]);
            $universityId = $this->db->lastInsertId();

            // Hash password
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            // Insert into users table with university_id
            $stmt = $this->db->prepare("INSERT INTO users (email, password, role, university_id) VALUES (?, ?, 'university_admin', ?)");
            $stmt->execute([
                $data['email'],
                $hashedPassword,
                $universityId
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Logout user
     */
    public function logout() {
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        return true;
    }

    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }

    /**
     * Check if user is a university admin
     */
    public function isUniversityAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'university_admin';
    }
}
?>