document.addEventListener("DOMContentLoaded", function() {
    var preloader = document.getElementById('site-preloader');
    var progress = 1;
    var progressInterval = setInterval(function() {
        if (progress >= 100) {
            clearInterval(progressInterval);
            // Add 'loaded' class for transition effect
            preloader.classList.add('loaded');
            // Hide the preloader after the transition is complete
            setTimeout(function() {
                preloader.style.display = 'none';
            }, 500); // This timeout duration should match the CSS transition duration
        } else {
            progress++;
            preloader.querySelector('.pace-progress').setAttribute('data-progress', progress);
        }
    }, 10); // Adjust the time interval as needed
});
