<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/cards.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <title>Folder Words</title>
</head>

<body>
    <header>
        <div class="first_row">
            <a href="review.php"><i class="fas fa-arrow-left"></i></a>
            <h2>Units</h2>
        </div>
    </header>
    <div class="score0">
        <div class="score1" id="score1"></div>
    </div>
    <div id="marg"></div>
    <div id="card-container">
        <?php
        require_once "../BACK/function.php";

        if (
            isset($_SESSION['user_id'])
            && isset($_SESSION['role_id'])
            && isset($_GET['folder_id'])
            && !empty($_GET['folder_id'])
        ) {
            $f_id = $_GET['folder_id'];
            $con = db_connect();
            $sql = "SELECT `word_id` from `folder_word` where `folder_id`=$f_id;";
            if ($res = $con->query($sql)) {
                if ($res->num_rows) {
                    while ($row = $res->fetch_assoc()) {
                        $w_id = $row['word_id'];
                        $sql = "SELECT `key`,`value` from `words` where `id`=$w_id;";
                        if ($res = $con->query($sql)) {
                            if ($row = $res->fetch_assoc()) {
                                $key = $row['key'];
                                $value = $row['value'];
                                ?>
                                <div class="word card">
                                    <i class="fas fa-arrow-left" id="back"></i>
                                    <a href="../BACK/drop_f_word.php?f_id=<?= $f_id ?>&w_id=<?= $w_id ?>">
                                        <i class="fas fa-trash" id="drop"></i>
                                    </a>
                                    <div class="front_card">
                                        <div class="content">
                                            <div class="text"><?= htmlspecialchars($key) ?></div>
                                            <button class="button" onclick="flipCard(this)">Flip</button>
                                            <button class="button" onclick="skipCard(this)">Skip</button>
                                        </div>
                                    </div>
                                    <div class="back_card">
                                        <div class="content">
                                            <div class="text"><?= htmlspecialchars($value) ?></div>
                                            <button class="button flip" onclick="flipCard(this)">Flip</button>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                die("<h3 style='color:#fff;'>No key/value for this word?!</h3>");
                            }
                        } else {
                            die($con->error);
                        }
                    }
                } else {
                    die("<h3 style='color:#fff;'>No words in this folder</h3>");
                }
            } else {
                die($con->error);
            }
        }
        ?>
    </div>
    <script>
        const words = document.querySelectorAll('.word');
        const totalWords = words.length;
        let currentWordIndex = 0;

        // Initialize the first word as visible
        if (totalWords > 0) {
            words[0].style.display = 'block';
            updateProgressBar();
        }

        function skipCard(button) {
            if (totalWords > 0) {
                words[currentWordIndex].style.display = 'none'; // Hide the current word
                currentWordIndex = (currentWordIndex + 1) % totalWords; // Move to the next word
                words[currentWordIndex].style.display = 'block'; // Show the next word
                updateProgressBar(); // Update the progress bar
            }
        }

        function updateProgressBar() {
            const progressBar = document.getElementById('score1');
            progressBar.style.width = ((currentWordIndex + 1) * 100) / totalWords + "%";
        }

        function flipCard(button) {
            const card = button.closest('.card');
            card.querySelector('.front_card').classList.toggle('hidden');
            card.querySelector('.back_card').classList.toggle('hidden');
        }
    </script>
</body>

</html>
