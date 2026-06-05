<?php
$currentPage = basename($_SERVER['PHP_SELF'] ?? '');
$navItems = [
    'admin1_pannel.php' => 'Dashboard',
    'add_test.php' => 'Add Test',
    'show_test.php' => 'Tests',
    'add-subject.php' => 'Add Subject',
    'show-subject.php' => 'Subjects',
    'add-questions.php' => 'Add Questions',
    'show-question.php' => 'Questions',
    'show-users.php' => 'Students',
    'show_user_answers.php' => 'Answer Review',
];
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-badge">PAF</div>
        <div>
            <h2>Admin Panel</h2>
            <p>Tests and results</p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php foreach ($navItems as $file => $label): ?>
            <a class="sidebar-link <?= $currentPage === $file ? 'active' : '' ?>" href="<?= htmlspecialchars($file, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>
