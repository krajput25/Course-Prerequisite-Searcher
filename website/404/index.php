<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error Page</title>
    <link rel="stylesheet" href="/404/404.css">
    <style>
        /* Hide the default cursor */
        body {
            cursor: none;
        }
        /* Style for the image that will follow the cursor */
        #nyan-cat-cursor {
            position: absolute;
            pointer-events: none; /* Allows clicking through the image */
            z-index: 9999;
            display: none; /* Start with the image hidden */
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div>
            <h1 data-h1="404">404</h1>
            <p data-p="NOT FOUND">NOT FOUND</p>
        </div>
        <button id="elusiveButton" style="position: absolute; z-index: 1000;">Go Home</button>
    </div>
    <div id="particles-js"></div>
    <img src="\404\nyan-gif.gif" id="nyan-cat-cursor" alt="Nyan Cat Cursor"/>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="/404/particles.js"></script>
    <script>
        // The following script makes the Nyan Cat gif follow the cursor
        document.addEventListener('mousemove', function(e) {
            var nyanCatCursor = document.getElementById('nyan-cat-cursor');
            // Show the image when the mouse moves
            nyanCatCursor.style.display = 'block';
            // Offset the position slightly so Nyan Cat appears to be centered on the cursor
            nyanCatCursor.style.left = (e.pageX - 16) + 'px';
            nyanCatCursor.style.top = (e.pageY - 16) + 'px';
        });
    </script>

<script>
// Variables to control button movement
let moveButtonEnabled = true;
let ctrlKeyPressed = false;

// Function to move the button to a random position
function moveButton() {
    if (!moveButtonEnabled) return; // Don't move the button if movement is disabled

    var viewportWidth = window.innerWidth;
    var viewportHeight = window.innerHeight;

    var newX = Math.random() * (viewportWidth - elusiveButton.offsetWidth);
    var newY = Math.random() * (viewportHeight - elusiveButton.offsetHeight);

    elusiveButton.style.top = newY + 'px';
    elusiveButton.style.left = newX + 'px';
}

// Function to calculate and set the initial button position
function setInitialButtonPosition() {
    var h1 = document.querySelector('.error-page h1');
    var p = document.querySelector('.error-page p');
    var h1Rect = h1.getBoundingClientRect();
    var pRect = p.getBoundingClientRect();

    // Calculate the vertical middle point between "404" and "NOT FOUND"
    // Adjust the vertical position slightly by subtracting a small value (e.g., 10px)
    var middleY = h1Rect.bottom + (pRect.top - h1Rect.bottom) / 2 - elusiveButton.offsetHeight / 2 - 5;
    
    // Calculate the horizontal center of the window
    var centerX = (window.innerWidth - elusiveButton.offsetWidth) / 2;

    elusiveButton.style.top = middleY + 'px';
    elusiveButton.style.left = centerX + 'px';
}

// Getting the button element
var elusiveButton = document.getElementById('elusiveButton');

// Event listener for keydown
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey) {
        ctrlKeyPressed = true;
    }
});

// Event listener for keyup
document.addEventListener('keyup', function(e) {
    if (e.key === "Control") {
        ctrlKeyPressed = false;
    }
});

// Add mousemove event listener to the document
document.addEventListener('mousemove', function(event) {
    moveButtonEnabled = !ctrlKeyPressed; // Disable movement only when CTRL is pressed

    var buttonRect = elusiveButton.getBoundingClientRect();
    var buttonX = buttonRect.left + buttonRect.width / 2; // X coordinate of the button's center
    var buttonY = buttonRect.top + buttonRect.height / 2; // Y coordinate of the button's center

    // Check if the cursor is within 100 pixels of the button
    if (Math.abs(event.clientX - buttonX) < 100 && Math.abs(event.clientY - buttonY) < 100 && !ctrlKeyPressed) {
        moveButton();
    }
});

// Set initial button position as soon as DOM is loaded
document.addEventListener('DOMContentLoaded', setInitialButtonPosition);

// Optional: Add a resize event listener to reset the button's initial position when the window is resized
window.addEventListener('resize', setInitialButtonPosition);

elusiveButton.addEventListener('click', function() {
    window.location.href = '/'; // Redirect to root directory
});

</script>






    <script src="/404/parrots.js"></script>

</body>
</html>
