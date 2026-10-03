const hover_audio = new Audio("../sounds/robot_hover.wav");
const click_audio = new Audio('../sounds/robot_click.wav');
const myDiv = document.querySelectorAll('.main a div');
myDiv.forEach(div => {
    div.addEventListener('mouseover', function() {
        hover_audio.play();
    });
    div.addEventListener('mouseout', function() {
        hover_audio.pause();
        hover_audio.currentTime = 0
    });
    div.addEventListener('click', function() {
        const click_duration = 998;
        setTimeout(function() {
            window.location.href = div.parentElement.href;
        }, click_duration);
        event.preventDefault();
        click_audio.play();
    });
});