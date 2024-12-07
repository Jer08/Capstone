const addInstructor = document.getElementById("addInstructor");
const addInstructorForm = document.getElementById("addInstructorForm");
addInstructor.addEventListener("click", function () {
  addInstructorForm.style.display = "block";
  overlay.style.display = "block";
  document.body.style.overflow = "hidden";
});
var closeButtons = document.querySelectorAll(" #addInstructorForm .close");

closeButtons.forEach(function (closeButton) {
  closeButton.addEventListener("click", function () {
    addInstructorForm.style.display = "none";
    overlay.style.display = "none";
    document.body.style.overflow = "auto";
  });
});
