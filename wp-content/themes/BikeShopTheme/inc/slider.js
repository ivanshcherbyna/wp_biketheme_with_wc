//*****SCRIPT FOR SLIDER MANUAL
var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
    showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
    showSlides(slideIndex = n);

}

function showSlides(n) {

    var i;
    var slides = document.getElementsByClassName("mySlides");
    var lines = document.getElementsByClassName("line");
    var greyClockItem = document.getElementsByClassName("scroll-item-center-before");
    var yelowClockItem = document.getElementsByClassName("scroll-item-center-after");

   // var stepsClock = timer/clockItem[0].clientHeight;
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < lines.length; i++) {
        lines[i].className = lines[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    lines[slideIndex-1].className += " active";
    //slides[slideIndex-1].className += " active";
    //clockItem[0].style.height= clockItem.offsetHeight-((slideIndex-1)*stepHeight)+'px';
    //console.log(clockItem.offsetHeight);

}
//*****SCRIPT FOR SLIDER SHOW
var sliderIndex = 0;
carousel();

function carousel() {
    var timer ='20000';
    var i;
    var x = document.getElementsByClassName("mySlides");
    var lines = document.getElementsByClassName("line");
    //var clockItem = document.getElementsByClassName("scroll-item-center-before");
    //var stepsClock = timer/clockItem[0].clientHeight;
   // console.log(stepsClock);
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    sliderIndex++;
    if (sliderIndex > x.length) {sliderIndex = 1}
    x[sliderIndex-1].style.display = "block";


    if (sliderIndex > x.length) {slideIndex = 1}
    for (i = 0; i < lines.length; i++) {
        lines[i].className = lines[i].className.replace(" active", "");
        lines[i].style.width = 100/lines.length+'%';

    }
    x[sliderIndex-1].style.display = "block";
    lines[sliderIndex-1].className += " active";
   // clockItem[0].style.height='30px';
    setTimeout(carousel, timer); // Change image every 20 seconds
    //*****SCRIPT FOR SLIDER
}