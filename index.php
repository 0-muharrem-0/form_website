<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

$limit = 7;
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

$panel = isset($_POST['panel']) ? $_POST['panel'] : '';

function fetchTopics($pdo, $category, $page, $limit) {
    try {
        $offset = ($page - 1) * $limit;
        $stmt = $pdo->prepare("
            SELECT topics.*, users.username 
            FROM topics 
            LEFT JOIN users ON topics.user_id = users.id 
            WHERE topics.category = :category 
            ORDER BY topics.created_at DESC 
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "SQL Sorgu hatası: " . $e->getMessage();
        return [];
    }
}

function fetchTotalPages($pdo, $category, $limit) {
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM topics WHERE category = :category");
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
        $totalTopics = $stmt->fetchColumn();
        return ceil($totalTopics / $limit);
    } catch (PDOException $e) {
        echo "SQL Sorgu hatası: " . $e->getMessage();
        return 1;
    }
}

$page1 = isset($_POST['page1']) ? (int)$_POST['page1'] : 1;
$page2 = isset($_POST['page2']) ? (int)$_POST['page2'] : 1;
$page3 = isset($_POST['page3']) ? (int)$_POST['page3'] : 1;
$page4 = isset($_POST['page4']) ? (int)$_POST['page4'] : 1;
$page5 = isset($_POST['page5']) ? (int)$_POST['page5'] : 1;

$topics1 = fetchTopics($pdo, 'Bilgisayar Programcılığı', $page1, $limit);
$totalPages1 = fetchTotalPages($pdo, 'Bilgisayar Programcılığı', $limit);

$topics2 = fetchTopics($pdo, 'Elektrik Bölümü', $page2, $limit);
$totalPages2 = fetchTotalPages($pdo, 'Elektrik Bölümü', $limit);

$topics3 = fetchTopics($pdo, 'Makine', $page3, $limit);
$totalPages3 = fetchTotalPages($pdo, 'Makine', $limit);

$topics4 = fetchTopics($pdo, 'Tekstil', $page4, $limit);
$totalPages4 = fetchTotalPages($pdo, 'Tekstil', $limit);

$topics5 = fetchTopics($pdo, 'İnşaat Teknolojisi', $page5, $limit);
$totalPages5 = fetchTotalPages($pdo, 'İnşaat Teknolojisi', $limit);

function generatePaginationButtons($totalPages, $panelId) {
    $buttons = '';
    for ($i = 1; $i <= $totalPages; $i++) {
        $buttons .= "<button class='pagination-btn' data-page='$i' data-panel='$panelId'>$i</button>";
    }
    return $buttons;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

    
    </script>
</head>
<body>

<?php include 'navbar.php'; ?>
<?php include 'head.php'; ?>
    <div class="page-container">
        <!-- Side Menü -->
        <div class="side-menu">
        <h3>FORMLAR</h3>
        <ul>
            <li><a href="#panel1"><i class="fas fa-laptop"></i> BİLGİSAYAR</a></li>
            <li><a href="#panel2"><i class="fas fa-bolt"></i> ELEKTRİK</a></li>
            <li><a href="#panel3"><i class="fas fa-cogs"></i> MAKİNE</a></li>
            <li><a href="#panel4"><i class="fas fa-tshirt"></i> TEKSTİL</a></li>
            <li><a href="#panel5"><i class="fas fa-building"></i> İNŞAAT</a></li>
        </ul>
    </div>

        <!-- Panel 1 -->
        <div class="panel-container" id="panel1"  style="margin-top: 40px;">
            <h2>Bilgisayar Programcılığı Konuları</h2>
            <div class="topics">
                <?php foreach ($topics1 as $topic): ?>
                    <div class="topic">
                        <h4><?= htmlspecialchars($topic['title']) ?></h4>
                        <p>Oluşturan: <?= htmlspecialchars($topic['username']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pagination">
                <?= generatePaginationButtons($totalPages1, 'panel1') ?>
            </div>
        </div>

        <!-- Panel 2 -->
        <div class="panel-container" id="panel2"  style="margin-top: 40px;">
            <h2>Elektrik Programcılığı Konuları</h2>
            <div class="topics">
                <?php foreach ($topics2 as $topic): ?>
                    <div class="topic">
                        <h4><?= htmlspecialchars($topic['title']) ?></h4>
                        <p>Oluşturan: <?= htmlspecialchars($topic['username']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pagination">
                <?= generatePaginationButtons($totalPages1, 'panel2') ?>
            </div>
        </div>

                <!-- Panel 3 -->
                <div class="panel-container" id="panel3"  style="margin-top: 40px;">
            <h2>Makine Bölümü Konuları</h2>
            <div class="topics">
                <?php foreach ($topics3 as $topic): ?>
                    <div class="topic">
                        <h4><?= htmlspecialchars($topic['title']) ?></h4>
                        <p>Oluşturan: <?= htmlspecialchars($topic['username']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pagination">
                <?= generatePaginationButtons($totalPages3, 'panel3') ?>
            </div>
        </div>


                <!-- Panel 4 -->
                <div class="panel-container" id="panel4"  style="margin-top: 40px;">
            <h2>Tekstil Bölümü Konuları</h2>
            <div class="topics">
                <?php foreach ($topics4 as $topic): ?>
                    <div class="topic">
                        <h4><?= htmlspecialchars($topic['title']) ?></h4>
                        <p>Oluşturan: <?= htmlspecialchars($topic['username']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pagination">
                <?= generatePaginationButtons($totalPages4, 'panel4') ?>
            </div>
        </div>

                <!-- Panel 5 -->
                <div class="panel-container" id="panel5" style="margin-top: 40px;">
            <h2>İnşaat Teknolojisi Bölümü Konuları</h2>
            <div class="topics">
                <?php foreach ($topics5 as $topic): ?>
                    <div class="topic">
                        <h4><?= htmlspecialchars($topic['title']) ?></h4>
                        <p>Oluşturan: <?= htmlspecialchars($topic['username']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pagination">
                <?= generatePaginationButtons($totalPages5, 'panel5') ?>
            </div>
        </div>

    </div>
    <?php include 'footer.php'; ?>
    <script>
    $(document).ready(function() {
        // Pagination düğmelerine tıklama olayını yönet
        $('.pagination-btn').on('click', function() {
            const page = $(this).data('page');
            const panel = $(this).data('panel');
            
            // AJAX ile sayfa içeriğini güncelle
            $.ajax({
                type: 'POST',
                url: 'load_topics.php',
                data: { page: page, panel: panel },
                success: function(response) {
                    $('#' + panel + ' .topics').html(response); // Sadece konuları değiştir
                },
                error: function(xhr, status, error) {
                    console.error("AJAX hatası:", status, error);
                }
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
    const element = document.querySelector('[data-page="1"]');
    if (element) {
        element.click();
    } else {
        console.error("Element bulunamadı!");
    }
    $(document).ready(function() {
    document.querySelector('#panel1 > div.pagination > button:nth-child(1)').click();
});

$(document).ready(function() {
    document.querySelector('#panel2 > div.pagination > button:nth-child(1)').click();
});

$(document).ready(function() {
    document.querySelector('#panel3 > div.pagination > button:nth-child(1)').click();
});

$(document).ready(function() {
    document.querySelector('#panel4 > div.pagination > button').click();
});

$(document).ready(function() {
    document.querySelector('#panel5 > div.pagination > button').click();
});
});

    </script>
  <script src="asset/toggle.js"></script>
  <script src="asset/snow.js"></script>
</body>
</html>
