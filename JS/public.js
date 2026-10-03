/* const lis=document.querySelectorAll('header a');
    Array.from(lis).forEach(e => {
        e.addEventListener('click',()=>{
            document.querySelector('.active')?.classList.remove('active');
            e.classList.add('active');
        })
    })
 the effect happend for a moment  !!
*/
function goDown() {
    const location = window.location;
    (location.hash != '#A1') ? location.hash = '#A1' : console.log('');
}
const nav_li = document.querySelectorAll('header li');
const windowPath = window.location.pathname;
var audio = new Audio('sounds/click_head.wav');
// windowPath: "/home.html"
// navLinks[0].href :"http://127.0.0.1:5501/home.html"
// Function to add 'active' class and change color for the active link
function setActiveLink() {
    nav_li.forEach(link => {
        if (link.parentElement.href === window.location.href) {
            link.parentElement.classList.add('active');
            link.childNodes[0].style.color = '#000';
        }
    });
}

// Add event listeners to the links
nav_li.forEach(link => {
    link.addEventListener('click', function (event) {
        // Prevent the default behavior of the link
        event.preventDefault();

        // Set 'active' class and color for the clicked link
        link.parentElement.classList.add('active');
        link.childNodes[0].style.color = '#000';

        // Store the active link in sessionStorage
        sessionStorage.setItem('activeLink', link.parentElement.href);
    });
});

// Check if there's a stored active link and apply 'active' class and color when the page loads
document.addEventListener('DOMContentLoaded', function () {
    var activeLink = sessionStorage.getItem('activeLink');
    if (activeLink) {
        // Clear active class and color for all links
        nav_li.forEach(link => {
            link.parentElement.classList.remove('active');
            link.childNodes[0].style.color = ''; // Restore default color
        });
        // Set active class and color for the stored active link
        setActiveLink();
    }
});


nav_li.forEach(link => {
    link.addEventListener('click', function () {
        setTimeout(() => {
            window.location.href = link.parentElement.href;
        }, 590);
        audio.play();
        event.preventDefault();
    });
});