<?php
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Varsayılan sayfa 1 olarak ayarlandı
global $panelId; // $panelId değişkenini global yaparak dahil ediyoruz
global $totalPages1; // $totalPages1 değişkenini global yaparak dahil ediyoruz
?>

<table class="topics-table">
    <thead>
        <tr>
            <th>#</th>
            <th><i class="fas fa-book-open"></i> Konu</th>
            <th><i class="fas fa-comments"></i> Forumu</th>
            <th><i class="fas fa-reply"></i> Cevap</th>
            <th><i class="fas fa-eye"></i> Görüntüleme</th>
            <th><i class="fas fa-calendar-alt"></i> Gönderim</th>
            <th><i class="fas fa-user"></i> Son yazan</th>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($topics) && count($topics) > 0): ?>
            <?php foreach ($topics as $index => $topic): ?>
                <tr class="topic-item">
                    <td><?= $index + 1 ?></td>
                    <td><a href="topic.php?id=<?= $topic['id'] ?>"><i class="fas fa-book"></i> <?= htmlspecialchars($topic['title']) ?></a></td>
                    <td><?= htmlspecialchars($topic['forum']) ?></td>
                    <td><?= htmlspecialchars($topic['replies']) ?></td>
                    <td><?= htmlspecialchars($topic['views']) ?></td>
                    <td><?= htmlspecialchars($topic['created_at']) ?></td>
                    <td><?= htmlspecialchars($topic['username']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Henüz hiçbir konu bulunmamaktadır.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="pagination">
    <?php if ($page > 1): ?>
        <button data-page="<?= $page - 1 ?>" data-panel="panel1"><i class="fas fa-chevron-left"></i> Önceki</button>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $totalPages1; $i++): ?>
        <button data-page="<?= $i ?>" data-panel="panel1" class="<?= $i === $page ? 'active' : '' ?>"><i class="fas fa-circle"></i> <?= $i ?></button>
    <?php endfor; ?>
    <?php if ($page < $totalPages1): ?>
        <button data-page="<?= $page + 1 ?>" data-panel="panel1">Sonraki <i class="fas fa-chevron-right"></i></button>
    <?php endif; ?>
</div>
