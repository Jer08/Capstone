const addLectureButton = document.getElementById("addInstructorButton");
const addLectureForm = document.getElementById("form");
const overlay = document.getElementById("overlay");

addLectureButton.addEventListener("click", function () {
  addLectureForm.style.display = "block";
  overlay.style.display = "block";
  document.body.style.overflow = "hidden"; // Disable scroll when form is shown
});

const closeButtons = document.querySelectorAll("#form .close");

closeButtons.forEach(function (closeButton) {
  closeButton.addEventListener("click", function () {
    addLectureForm.style.display = "none";
    overlay.style.display = "none";
    document.body.style.overflow = "auto"; // Enable scroll when form is closed
  });
});

overlay.addEventListener("click", function () {
  addLectureForm.style.display = "none";
  overlay.style.display = "none";
  document.body.style.overflow = "auto"; // Enable scroll when overlay is clicked
});
