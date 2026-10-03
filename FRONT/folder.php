<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card Stack</title>
    <link rel="stylesheet" href="../CSS/cards_plus.css">
    <link rel="stylesheet" href="../CSS/cards.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-****" crossorigin="anonymous" />
    <style>
        /* table{
            background-color: #0a80ff;
        } */
    </style>
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
    <script>
        // declare words 
        let words = {};
    </script>
    <?php
    require_once "../BACK/function.php";
    $f_k = "";
    $f_v = "";
    if (
        isset($_SESSION['user_id'])
        && isset($_SESSION['role_id'])
        && isset($_GET['folder_id'])
        && !empty($_GET['folder_id'])
    ) {
        $f_id = $_GET['folder_id'];
        // echo "<script>alert('$f_id');</script>";
        $con = db_connect();
        $sql = "SELECT `word_id` from `folder_word` where `folder_id`=$f_id;";
        if ($res = $con->query($sql)) {
            if ($res->num_rows) {
                // echo "words: $res->num_rows folder_id`=$f_id";
                while ($row = $res->fetch_assoc()) {
                    $w_id = $row['word_id'];
                    $sql = "SELECT `key`,`value` from `words` where `id`=$w_id;";
                    if ($res = $con->query($sql)) {
                        if ($row = $res->fetch_assoc()) {
                            $f_k = $row['key'];
                            $f_v = $row['value'];
    ?>
                            <script>
                                // php -> js 
                                words['<?= $f_k ?>'] = '<?= $f_v ?>';
                            </script>
    <?php
                        } else {
                            die("<h3 style='color:#fff;'>no key|value for this word ?!</h2>");
                        }
                    } else {
                        die($con->error);
                    }
                } // getting word ids
            } else {
                die("<h3 style='color:#fff;'>no word in this folder</h3>");
            }
        } else {
            die($con->error);
        }
    }
    ?>
    <div class="progress-bar" id="progress-bar"></div>
    <div id="card-container">
        <table>
            <tr class="first">
                <td class="b"><i class="fas fa-arrow-left" id="back"></i></td>
                <td ></td>
                <td class="d"><a href="../BACK/drop_f_word.php?f_id=<?= $f_id ?>&w_id=<?= $w_id ?>"><i class="fas fa-trash" id="drop"></i></a></td>
            </tr>
            <tr class="center">
                <td colspan="3" id="word"><?= $f_k ?></td>
            </tr>
            <tr class="last">

                <td id="f" data-sens="1">flip</td>
                <td></td>
                <td id="s">skip</td>
            </tr>
        </table>
    </div>
    <script>
        // Initial setup
        const flip = document.getElementById('f');
        const word = document.getElementById('word');
        const skip = document.getElementById('s');
        const back = document.getElementById('back');
        const progressBar = document.getElementById('progress-bar');

        // Get keys and values from words object
        const keys = Object.keys(words);
        const values = Object.values(words);

        // Index for tracking the current word
        let currentWordIndex = 0;
        let totalWords = keys.length;

        // Display the initial word
        word.textContent = keys[currentWordIndex];

        // Event listener for the flip button
        flip.addEventListener('click', function() {
            let sens = flip.dataset.sens; // Get the current value of data-sens
            if (sens == 0) { // k -> v
                word.textContent = words[word.textContent];
                flip.dataset.sens = 1;
            } else if (sens == 1) { // v -> k
                word.textContent = keys[values.indexOf(word.textContent)];
                flip.dataset.sens = 0;
            }
        });

        // Event listener for the skip button
        skip.addEventListener('click', function() {
            currentWordIndex = (currentWordIndex + 1) % totalWords;
            word.textContent = keys[currentWordIndex];
            flip.dataset.sens = 0; // Reset to key mode
            updateProgressBar();
        });

        // Event listener for the back button
        back.addEventListener('click', function() {
            currentWordIndex = (currentWordIndex - 1 + totalWords) % totalWords;
            word.textContent = keys[currentWordIndex];
            flip.dataset.sens = 0; // Reset to key mode
            updateProgressBar();
        });

        // Update progress bar function
        function updateProgressBar() {
            const progress = ((currentWordIndex + 1) / totalWords) * 100;
            // progressBar.style.width = progress + '%';
        }

        // Initialize the progress bar
        updateProgressBar();
    </script>
</body>

</html>