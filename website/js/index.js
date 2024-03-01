const overlay = document.getElementById("overlay");
const closeOverlayButton = document.getElementById("closeOverlay");
const helpButton = document.querySelector(".helpbutton img");

function openOverlay() {
    overlay.style.display = "block";
}

function closeOverlay() {
    overlay.style.display = "none";
}

// Event listeners
helpButton.addEventListener("click", openOverlay);
closeOverlayButton.addEventListener("click", closeOverlay);
