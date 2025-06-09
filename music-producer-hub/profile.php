<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = false;

// Get user information
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = trim($_POST['bio']);
    $email = trim($_POST['email']);
    
    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($_FILES['profile_picture']['type'], $allowed_types)) {
            $errors[] = "Invalid file type. Please upload a JPEG, PNG, or GIF image.";
        } elseif ($_FILES['profile_picture']['size'] > $max_size) {
            $errors[] = "File is too large. Maximum size is 5MB.";
        } else {
            $upload_dir = 'uploads/profile_pictures/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                // Delete old profile picture if exists
                if ($user['profile_picture'] && file_exists($user['profile_picture'])) {
                    unlink($user['profile_picture']);
                }
                $profile_picture = $upload_path;
            } else {
                $errors[] = "Failed to upload profile picture.";
            }
        }
    }
    
    // Update user information if no errors
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE users SET bio = ?, email = ?" . 
                             (isset($profile_picture) ? ", profile_picture = ?" : "") . 
                             " WHERE id = ?");
        
        $params = [$bio, $email];
        if (isset($profile_picture)) {
            $params[] = $profile_picture;
        }
        $params[] = $user_id;
        
        try {
            $stmt->execute($params);
            $success = true;
            // Refresh user data
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        } catch (PDOException $e) {
            $errors[] = "Failed to update profile.";
        }
    }
}

// Get user's projects
$stmt = $pdo->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$projects = $stmt->fetchAll();

// Get user's samples
$stmt = $pdo->prepare("SELECT * FROM samples WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$samples = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Music Producer Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-black">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-music"></i> Music Producer Hub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="projects.php">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="samples.php">Samples</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="collaborate.php">Collaborate</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="messages.php" class="btn btn-outline-light me-2">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="<?php echo $user['profile_picture'] ?? 'assets/images/default-profile.jpg'; ?>" 
                         alt="Profile Picture" class="profile-picture mb-3">
                </div>
                <div class="col-md-9">
                    <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                    <p class="lead"><?php echo htmlspecialchars($user['bio'] ?? 'No bio yet'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="container py-5">
        <div class="row">
            <!-- Edit Profile -->
            <div class="col-md-4">
                <div class="card bg-black mb-4">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Edit Profile</h3>
                        
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                Profile updated successfully!
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="profile.php" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="4"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Projects and Samples -->
            <div class="col-md-8">
                <!-- Projects -->
                <h3 class="mb-4">My Projects</h3>
                <div class="row">
                    <?php foreach ($projects as $project): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card bg-black">
                                <?php if ($project['cover_image']): ?>
                                    <img src="<?php echo htmlspecialchars($project['cover_image']); ?>" 
                                         class="card-img-top" alt="Project Cover">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($project['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($project['description']); ?></p>
                                    <div class="audio-player">
                                        <audio controls>
                                            <source src="<?php echo htmlspecialchars($project['audio_file']); ?>" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Samples -->
                <h3 class="mb-4 mt-5">My Samples</h3>
                <div class="row">
                    <?php foreach ($samples as $sample): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card bg-black">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($sample['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($sample['description']); ?></p>
                                    <div class="audio-player">
                                        <audio controls>
                                            <source src="<?php echo htmlspecialchars($sample['audio_file']); ?>" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 