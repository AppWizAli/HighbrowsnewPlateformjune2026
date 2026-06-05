<?php
require_once __DIR__ . '/admin_helpers.php';

$currentPage = basename($_SERVER['PHP_SELF'] ?? '');
$navItems = [
    [
        'file' => 'admin1_pannel.php',
        'label' => 'Dashboard',
        'caption' => 'overview',
        'match' => ['admin1_pannel.php'],
    ],
    [
        'file' => 'show_test.php',
        'label' => 'Tests',
        'caption' => 'add and manage',
        'match' => ['show_test.php', 'add_test.php', 'insert_test.php', 'update_test.php'],
    ],
    [
        'file' => 'show-subject.php',
        'label' => 'Subjects',
        'caption' => 'organize sections',
        'match' => ['show-subject.php', 'add-subject.php', 'insert_subjects.php'],
    ],
    [
        'file' => 'show-question.php',
        'label' => 'Questions',
        'caption' => 'build question bank',
        'match' => ['show-question.php', 'add-questions.php', 'edit_question.php', 'update_question.php', 'insert_questions.php'],
    ],
    [
        'file' => 'show-users.php',
        'label' => 'Students',
        'caption' => 'results and reports',
        'match' => ['show-users.php', 'show_user_answers.php', 'show-users-result.php', 'fetch_results.php'],
    ],
];
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-mark">PAF</div>
        <div>
            <strong>Admin Panel</strong>
            <span>minimal control center</span>
        </div>
    </div>

    <nav class="sidebar-nav" aria-label="Admin navigation">
        <?php foreach ($navItems as $item): ?>
            <?php $isActive = in_array($currentPage, $item['match'], true); ?>
            <a class="sidebar-link <?= $isActive ? 'active' : '' ?>" href="<?= pafAdminEsc($item['file']) ?>">
                <span><?= pafAdminEsc($item['label']) ?></span>
                <small><?= pafAdminEsc($item['caption']) ?></small>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>
