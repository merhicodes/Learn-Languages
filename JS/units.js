// window.addEventListener('scroll', function () {
//     var header = document.querySelector('header');
//     var scrollTop = window.scrollY;

//     // Calculate the opacity based on scroll position
//     var opacity = Math.min(scrollTop / 80, 1); // Adjust the value for smoother effect

//     // Set the background color with alpha based on opacity
//     header.style.backgroundColor = 'rgba(255, 255, 255, ' + opacity + ')';
//     if (scrollTop == 70) {// 70== the height of the header
//         // alert(opacity);// 0.875 
//     }
//     if (opacity > 0.875)
//         // header.style.color = 'rgba(255, 255, 255, ' + opacity + ')';
//     header.style.color='#009dff';
//     header.style.borderBottom = '2px solid rgba(0, 0, 255, ' + opacity + ')';
// });

const form = document.getElementById('main');
const add = document.getElementById('add');
const x = document.getElementById('x');
add.addEventListener('click', function () {
    form.style.top = '0vh';
})
x.addEventListener('click', function () {
    form.style.top = '100vh';
})
