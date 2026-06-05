<?php
require_once __DIR__ . '/admin_helpers.php';

$currentPage = basename($_SERVER['PHP_SELF'] ?? '');
$navItems = [
    [
        'file' => 'admin1_pannel.php',
        'label' => 'Dashboard',
        'icon' => '&#9635;',
        'match' => ['admin1_pannel.php'],
    ],
    [
        'file' => 'show_test.php',
        'label' => 'Tests',
        'icon' => '&#9638;',
        'match' => ['show_test.php', 'add_test.php', 'insert_test.php', 'update_test.php'],
    ],
    [
        'file' => 'show-subject.php',
        'label' => 'Subjects',
        'icon' => '&#9675;',
        'match' => ['show-subject.php', 'add-subject.php', 'insert_subjects.php'],
    ],
    [
        'file' => 'show-question.php',
        'label' => 'Questions',
        'icon' => '&#63;',
        'match' => ['show-question.php', 'add-questions.php', 'edit_question.php', 'update_question.php', 'insert_questions.php'],
    ],
    [
        'file' => 'show-users.php',
        'label' => 'Students',
        'icon' => '&#9787;',
        'match' => ['show-users.php', 'show_user_answers.php', 'show-users-result.php', 'fetch_results.php'],
    ],
];
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-mark">PAF</div>
        <div>
            <strong>Admin Panel</strong>
            <span>clean control</span>
        </div>
    </div>

    <nav class="sidebar-nav" aria-label="Admin navigation">
        <?php foreach ($navItems as $item): ?>
            <?php $isActive = in_array($currentPage, $item['match'], true); ?>
            <a class="sidebar-link <?= $isActive ? 'active' : '' ?>" href="<?= pafAdminEsc($item['file']) ?>">
                <span class="sidebar-link-row">
                    <span class="sidebar-icon" aria-hidden="true"><?= html_entity_decode($item['icon'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="sidebar-label"><?= pafAdminEsc($item['label']) ?></span>
                </span>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>
