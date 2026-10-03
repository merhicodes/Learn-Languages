<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/vocabulary.css">
    <link rel="stylesheet" href="../CSS/Quiz.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <title>Quiz</title>
</head>
<?php
require_once "../BACK/function.php";
if (
    isset($_SESSION['user_id'])
    && isset($_SESSION['role_id'])
    && isset($_SESSION['less_id'])
    && !empty($_SESSION['less_id'])
    && isset($_SESSION['lev'])
    && isset($_SESSION['unit_id'])
) {
    $less_id = $_SESSION['less_id'];
?>

    <body onload="generate_quiz()">
        <header>
            <div class="first_row">
                <a href="home.php"><i class="fas fa-arrow-left"></i></a>
                <h2>Quiz</h2>
                <i class="fa-solid fa-square-plus" style="opacity: 0;"></i>
            </div>
            <div class="main_score" style="opacity: 0;">
                <div class="score_container">
                    <div class="score"></div>
                </div>
                <span>100%</span>
            </div>
        </header>
        <div id="marg"></div>
        <main>
            <div class="sup_cont" id="0">
                <h2>no exam for this lesson</h2>
                <div class="cont">
                    <!-- <div class="selected"><i class="fas fa-check-circle"></i></div> -->
                    <div class="word">
                        <div class="inner_word"><span onclick="window.location.href = 'congratulation.php?win=1'">ok</span></div>
                    </div>
                    <div class="word">
                        <div class="inner_word"><span onclik="make_error()">-</span></div>
                    </div>
                    <div class="word">
                        <div class="inner_word"><span onclik="make_error()">-</span></div>
                    </div>
                    <div class="word">
                        <div class="inner_word"><span onclik="make_error()">-</span></div>
                    </div>
                    <div class="word">
                        <div class="inner_word"><span onclik="make_error()">-</span></div>
                    </div>
                    <div class="word">
                        <div class="inner_word"><span onclik="make_error()">-</span></div>
                    </div>
                </div>
        </main>
        <?php
        $php_words = [];
        $res = db_connect()->query("SELECT `key`, `value` FROM `words` WHERE `less_id` = $less_id;");
        if (!$res->num_rows) {
            header('location:congratulation.php?win=1');
            exit;
        } else {
            while ($row = $res->fetch_assoc()) {
                extract($row);
                $php_words[$key] = $value;
            }
        }
        ?>
        <script>
            let words = document.querySelectorAll('.word');
            let ans = '';
            const quetion = document.querySelector('.sup_cont h2');
            let words_meaning = {};
            <?php
            //the best API :)
            foreach ($php_words as $js_k => $js_v) { ?>
                words_meaning['<?= $js_k ?>'] = '<?= $js_v ?>';
            <?php } ?>
            // console.log(words_meaning);
            function generate_quiz() {
                // suppose that words_meaning is from vocabulary page
                const keys = Object.keys(words_meaning);
                const vals = Object.values(words_meaning);
                const answer = document.querySelectorAll(".inner_word span");
                if (keys.length >= 6) {
                    const quet = keys[getRandNb(0, keys.length - 1)];
                    ans = words_meaning[quet];
                    quetion.textContent = quet;
                    ans_index = getRandNb(0, 3); // where to put the answer
                    answer[ans_index].textContent = ans;
                    libre_divs = any_index_not(ans_index); // array of empty divs
                    libre_divs.forEach(div => {
                        let i = 1;
                        any_value = vals[getRandNb(0, vals.length - 1)];
                        while (not_unique(any_value, ans)) {
                            any_value = vals[getRandNb(0, vals.length - 1)];
                        }
                        answer[div].textContent = any_value;
                    });
                }
            }
            words.forEach(word => {
                word.addEventListener('click', function() {
                    // check
                    const user_ans = word.textContent.trim();
                    //  this means the user press ok
                    if (user_ans == ans || (user_ans=='ok' && !ans)) {
                        // alert(user_ans + "==" + ans);
                        window.location.href = 'congratulation.php?win=1';
                    } else {
                        console.log(user_ans + "==" + ans);
                        make_error();
                    }
                });
            });

            function make_error() {
                const error = new Audio('../sounds/error.wav');
                quetion.classList.add('error');
                error.play();
                setTimeout(function() {
                    quetion.classList.remove('error');
                }, 600);
            }

            function not_unique(value, ans) {
                // value : string
                const spans = document.querySelectorAll('.inner_word span');
                let values = [];
                spans.forEach(span => {
                    let value = span.textContent;
                    if (value == '-' || value == 'ok') value = ans;
                    values.push(value);
                })
                return Array.from(values).includes(value);
            }

            function any_index_not(x) {
                let ans = [];
                for (let i = 0; i <= 5; i++) {
                    if (i != x) {
                        ans.push(i);
                    }
                }
                return ans;
            }

            function getRandNb(min, max) {
                return Math.floor(Math.random() * (max - min + 1)) + min;
            }
        </script>
    <?php
}
    ?>
    </body>

</html>