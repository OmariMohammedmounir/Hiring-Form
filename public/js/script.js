document.addEventListener('DOMContentLoaded', function() {
  var salarieCheckbox = document.getElementById('isSalarie');
  var sectionSalarie = document.getElementById('sectionSalarie');

  function toggleSalarie() {
    if (salarieCheckbox.checked) {
      sectionSalarie.style.display = 'block';
    } else {
      sectionSalarie.style.display = 'none';
    }
  }

  salarieCheckbox.addEventListener('change', toggleSalarie);
  toggleSalarie(); // Initialize state on page load
});
