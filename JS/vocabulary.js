let words = document.querySelectorAll('.word');
let words_meaning={};
words.forEach(word =>{
    const inner_word=word.childNodes[1];
    words_meaning[inner_word.childNodes[0].textContent]=inner_word.childNodes[1].textContent;
});
words.forEach(word => {
    word.innerHTML += '<div class="selected"><i class="fas fa-check-circle"></i></div>';

});
words.forEach(word => {
    word.addEventListener('click', function () {
        if(word.children[1].style.opacity == 0){
            word.children[1].style.opacity = 1;

        }
        else{
            word.children[1].style.opacity = 0;

        }
    })
});

const folder = document.getElementById('folder_input');
const x = document.querySelector('.fa-times');
const main = document.querySelector('.main');
const add = document.querySelector('.fa-square-plus');
x.addEventListener('click', function () {
    main.style.top = '100vh';
});
add.addEventListener('click', function () {
    if (exit_selected().length > 0)
        main.style.top = '0px';
    else
        alert("Please select words");
});
function exit_selected() {
    let ans = new Array();
    words.forEach(word => {
        // console.log(word.children[1].style.opacity > 0);
        if (word.children[1].style.opacity > 0) {
            ans.push('1');
        }
    });
    return ans;
}
