<?php
require_once '../../config/database.php';
include 'includes/header.php';

// Stats
$stats = [
    'users' => $conn->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'students' => $conn->query("SELECT COUNT(*) FROM users WHERE role = 'siswa'")->fetchColumn(),
    'teachers' => $conn->query("SELECT COUNT(*) FROM users WHERE role = 'guru'")->fetchColumn(),
    'classes' => $conn->query("SELECT COUNT(*) FROM classes")->fetchColumn(),
];

// Recent Attendance Logs
$logs = $conn->query("
    SELECT al.*, u.full_name, c.class_name, sub.subject_name 
    FROM attendance_logs al
    JOIN users u ON al.student_id = u.id
    JOIN attendance_sessions asess ON al.session_id = asess.id
    JOIN schedules s ON asess.schedule_id = s.id
    JOIN classes c ON s.class_id = c.id
    JOIN subjects sub ON s.subject_id = sub.id
    ORDER BY al.submitted_at DESC LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <h3 class="mb-4">Dashboard Overview</h3>
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card card-custom">
                <div class="stat-card">
                    <div class="stat-icon bg-primary text-white">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?= $stats['users'] ?></h3>
                        <small class="text-muted">Total Pengguna</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card card-custom">
                <div class="stat-card">
                    <div class="stat-icon bg-success text-white">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?= $stats['students'] ?></h3>
                        <small class="text-muted">Total Siswa</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card card-custom">
                <div class="stat-card">
                    <div class="stat-icon bg-warning text-white">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?= $stats['teachers'] ?></h3>
                        <small class="text-muted">Total Guru</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card card-custom">
                <div class="stat-card">
                    <div class="stat-icon bg-info text-white">
                        <i class="fas fa-school"></i>
                    </div>
                    <div>
                        <h3 class="mb-0"><?= $stats['classes'] ?></h3>
                        <small class="text-muted">Total Kelas</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card card-custom p-4">
                <h5 class="mb-3">Aktivitas Presensi Terbaru</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Mapel</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= date('d M Y H:i', strtotime($log['submitted_at'])) ?></td>
                                <td><?= htmlspecialchars($log['full_name']) ?></td>
                                <td><?= htmlspecialchars($log['class_name']) ?></td>
                                <td><?= htmlspecialchars($log['subject_name']) ?></td>
                                <td><span class="badge bg-success"><?= $log['status'] ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

