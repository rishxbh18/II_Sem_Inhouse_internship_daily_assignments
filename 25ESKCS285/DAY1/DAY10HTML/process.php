<?php
// Session start kiya data temporary store karne ke liye (Bina database setup ke)
session_start();

// Agar session mein students array nahi hai, toh initialize karein default sample data ke sath
if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = [
        ['id' => 1, 'name' => 'rohit singh', 'branch' => 'Computer Science', 'email' => 'rahul@gmail.com'],
        ['id' => 2, 'name' => 'sonal sharma', 'branch' => 'Information Technology', 'email' => 'priya@gmail.com'],
        ['id' => 3, 'name' => 'amit kumar', 'branch' => 'Mechanical Engineering', 'email' => 'amit@gmail.com']
    ];
}

// Variables initialization for Update flow
$editMode = false;
$editStudent = ['id' => '', 'name' => '', 'branch' => '', 'email' => ''];

// 1. CREATE & UPDATE Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_student'])) {
    $id = $_POST['id'];
    $name = htmlspecialchars($_POST['name']);
    $branch = htmlspecialchars($_POST['branch']);
    $email = htmlspecialchars($_POST['email']);

    if (!empty($id)) {
        // Update existing record
        foreach ($_SESSION['students'] as &$student) {
            if ($student['id'] == $id) {
                $student['name'] = $name;
                $student['branch'] = $branch;
                $student['email'] = $email;
                break;
            }
        }
    } else {
        // Create new record with unique ID
        $newId = count($_SESSION['students']) > 0 ? max(array_column($_SESSION['students'], 'id')) + 1 : 1;
        $_SESSION['students'][] = ['id' => $newId, 'name' => $name, 'branch' => $branch, 'email' => $email];
    }
    header("Location: student_system.php");
    exit();
}

// 2. FETCH FOR EDIT Logic
if (isset($_GET['edit'])) {
    $editId = $_GET['edit'];
    foreach ($_SESSION['students'] as $student) {
        if ($student['id'] == $editId) {
            $editStudent = $student;
            $editMode = true;
            break;
        }
    }
}

// 3. DELETE Logic
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $_SESSION['students'] = array_filter($_SESSION['students'], function($student) use ($deleteId) {
        return $student['id'] != $deleteId;
    });
    $_SESSION['students'] = array_values($_SESSION['students']);
    header("Location: student_system.php");
    exit();
}

// 4. SEARCH & FILTER Logic
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$filteredStudents = $_SESSION['students'];

if ($searchQuery !== '') {
    $filteredStudents = array_filter($_SESSION['students'], function($student) use ($searchQuery) {
        return stripos($student['name'], $searchQuery) !== false || 
               stripos($student['branch'], $searchQuery) !== false || 
               stripos($student['email'], $searchQuery) !== false;
    });
}
?>
