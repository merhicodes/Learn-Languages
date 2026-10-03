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
    flip.addEventListener('click', function () {
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
    skip.addEventListener('click', function () {
        currentWordIndex = (currentWordIndex + 1) % totalWords;
        word.textContent = keys[currentWordIndex];
        flip.dataset.sens = 0; // Reset to key mode
        updateProgressBar();
    });

    // Event listener for the back button
    back.addEventListener('click', function () {
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
