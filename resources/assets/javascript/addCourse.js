
const  addCourseForm= document.getElementById("addCourseForm");
const addCourse = document.getElementById("addCourse");
const overlay = document.querySelector('#overlay');
const addSubject=document.getElementById('addSubject');
const addSubjectForm=document.getElementById("addSubjectForm")
const addDepartment=document.getElementById('addDepartment');
const addDepartmentForm=document.getElementById("addDepartmentForm");



addCourse.addEventListener("click", function() {
  addCourseForm.style.display = "block";
  overlay.style.display="block";
  document.body.style.overflow = 'hidden'; 


});
addSubject.addEventListener("click", function() {
    addSubjectForm.style.display = "block";
    overlay.style.display="block";
    document.body.style.overflow = 'hidden'; 
  
  
  });
  addDepartment.addEventListener("click", function() {
    addDepartmentForm.style.display = "block";
    overlay.style.display="block";
    document.body.style.overflow = 'hidden'; 
  
  
  });


  var closeButtons = document.querySelectorAll('#addCourseForm .close, #addSubjectForm .close, #addDepartmentForm .close');

  closeButtons.forEach(function(closeButton) {
      closeButton.addEventListener('click', function() {
          addCourseForm.style.display = "none";
          addSubjectForm.style.display = "none";
          addDepartmentForm.style.display="none";
          overlay.style.display = 'none';
          document.body.style.overflow = 'auto'; 
      });
  });
