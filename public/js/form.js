 // Toggle non-français section based on "Je suis français" checkbox
 document.addEventListener('DOMContentLoaded', function() {
    var isFrancaisCheckbox = document.getElementById('is_français');
    var nonFrancaisSection = document.getElementById('non_francais_section');
    
    function toggleNonFrancais() {
      // If "Je suis français" is checked, hide the extra fields.
      if (isFrancaisCheckbox.checked) {
        nonFrancaisSection.style.display = 'none';
      } else {
        nonFrancaisSection.style.display = 'flex';
      }
    }
    
    isFrancaisCheckbox.addEventListener('change', toggleNonFrancais);
    toggleNonFrancais(); // Set initial state.
  });
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