<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pdo = new PDO('mysql:host=localhost;dbname=forum', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_GET['id'];

// ID'nin geçerli olduğundan emin ol
if (!filter_var($id, FILTER_VALIDATE_INT)) {
    die("Geçersiz ID");
}

// Yorumları al
$commentsStmt = $pdo->prepare("SELECT comments.*, users.username FROM comments INNER JOIN users ON comments.user_id = users.id WHERE topic_id = ? ORDER BY created_at ASC");
$commentsStmt->execute([$id]);
$comments = $commentsStmt->fetchAll();

// Fonksiyon ile yorumları ve alt yorumları hiyerarşik olarak göster
function displayComments($pdo, $comments, $parent_id = 0, $level = 0, $parent_username = '') {
    foreach ($comments as $comment) {
        if ($comment['parent_id'] == $parent_id) {
            echo '<div class="comment" style="margin-left: ' . ($level * 20) . 'px; max-height: none;">';
            echo '<strong>' . htmlspecialchars($comment['username']) . ':</strong>';
            echo '<p>' . htmlspecialchars($comment['content']) . '</p>';
            echo '<small>' . htmlspecialchars($comment['created_at']) . '</small>';

            if ($parent_id != 0 && $parent_username != '') {
                echo '<small>Yanıtlanan: ' . htmlspecialchars($parent_username) . '</small>';
            }

            if (isset($_SESSION['user_id'])) {
                echo '<div class="button-container">';
                echo '<button type="button" class="show-reply-form">Yanıt Yaz</button>';
                echo '<button type="button" class="toggle-replies" onclick="toggleReplies(' . htmlspecialchars($comment['id']) . ')">Yanıtları Göster</button>';
                echo '</div>';
                echo '<div class="reply-form comment-form-container" style="display: none;">';
                echo '<textarea name="content" required data-parent-id="' . htmlspecialchars($comment['id']) . '" placeholder="Yanıtınızı yazın..."></textarea>';
                echo '<button type="button" onclick="submitReply(' . htmlspecialchars($comment['id']) . ', \'' . htmlspecialchars($comment['username']) . '\')">Gönder</button>';
                echo '</div>';
            }

            // Yanıt olup olmadığını kontrol et
            $repliesStmt = $pdo->prepare("SELECT COUNT(*) FROM comments WHERE parent_id = ?");
            $repliesStmt->execute([$comment['id']]);
            $replyCount = $repliesStmt->fetchColumn();

            echo '<div class="replies" id="replies-' . htmlspecialchars($comment['id']) . '" style="display: none;">';
            displayComments($pdo, $comments, $comment['id'], $level + 1, $comment['username']);
            echo '</div>';

            echo '</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yorumlar</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<section class="comments-section">
    <h2 style="color: white;">Yorumlar</h2>
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="comment-form-container">
            <input type="hidden" name="topic_id" value="<?= htmlspecialchars($id) ?>">
            <textarea name="content" required placeholder="Yorumunuzu yazın..."></textarea>
            <button type="button" onclick="submitComment()">Yorum Yap</button>
        </div>
    <?php endif; ?>

    <!-- Yorumları Göster -->
    <?php displayComments($pdo, $comments); ?>
</section>

<!-- JavaScript Kodu -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var showReplyFormButtons = document.querySelectorAll('.show-reply-form');
    var toggleRepliesButtons = document.querySelectorAll('.toggle-replies');

    showReplyFormButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var replyForm = this.closest('.comment').querySelector('.reply-form');

            if (replyForm.style.display === 'none' || replyForm.style.display === '') {
                replyForm.style.display = 'block';
                this.textContent = 'Yanıtı Gizle';
            } else {
                replyForm.style.display = 'none';
                this.textContent = 'Yanıt Yaz';
            }
        });
    });

    toggleRepliesButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var repliesContainer = this.closest('.comment').querySelector('.replies');
            var commentDiv = this.closest('.comment');

            if (repliesContainer.style.display === 'none' || repliesContainer.style.display === '') {
                showAllReplies(repliesContainer);
                repliesContainer.style.display = 'block';
                this.textContent = 'Yanıtları Gizle';
                commentDiv.style.maxHeight = 'none';
            } else {
                repliesContainer.style.display = 'none';
                this.textContent = 'Yanıtları Göster';
                commentDiv.style.maxHeight = '100px';
                var replyForm = commentDiv.querySelector('.reply-form');
                if (replyForm) {
                    replyForm.style.display = 'none';
                    var showReplyFormButton = commentDiv.querySelector('.show-reply-form');
                    if (showReplyFormButton) {
                        showReplyFormButton.textContent = 'Yanıt Yaz';
                    }
                }
            }
        });
    });

    function showAllReplies(container) {
        container.querySelectorAll('.replies').forEach(function(repliesDiv) {
            repliesDiv.style.display = 'block';
        });
    }
});

function submitComment() {
    const topic_id = document.querySelector('input[name="topic_id"]').value;
    const content = document.querySelector('.comment-form-container textarea[name="content"]').value.trim();

    if (!content) {
        alert("Yorumunuzu yazmalısınız.");
        return;
    }

    fetch('add_comment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `topic_id=${encodeURIComponent(topic_id)}&content=${encodeURIComponent(content)}&parent_id=0`
    })
    .then(response => response.text())
    .then(data => {
        fetch(`update_replies.php?topic_id=${encodeURIComponent(topic_id)}`)
        .then(() => location.reload())
        .catch(error => console.error('Error:', error));
    })
    .catch(error => console.error('Error:', error));
}

function submitReply(parent_id, parent_username) {
    const topic_id = document.querySelector('input[name="topic_id"]').value;
    const content = document.querySelector(`.comment-form-container textarea[data-parent-id="${parent_id}"]`).value.trim();

    if (!content) {
        alert("Yanıtınızı yazmalısınız.");
        return;
    }

    fetch('add_comment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `topic_id=${encodeURIComponent(topic_id)}&content=${encodeURIComponent(content)}&parent_id=${encodeURIComponent(parent_id)}`
    })
    .then(response => response.text())
    .then(data => {
        fetch(`update_replies.php?topic_id=${encodeURIComponent(topic_id)}`)
        .then(() => location.reload())
        .catch(error => console.error('Error:', error));
    })
    .catch(error => console.error('Error:', error));
}
</script>
</body>
</html>
