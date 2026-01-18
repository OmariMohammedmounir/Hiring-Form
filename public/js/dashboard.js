document.addEventListener('DOMContentLoaded', function() {
    // Token generation and copy functionality
    var generateBtn = document.getElementById("generate-token-btn");
    var tokenLinkInput = document.getElementById("token-link");
    var copyBtn = document.getElementById("copy-token-btn");
    
    generateBtn.addEventListener("click", function() {
        fetch('/weetel/public/generate-token-ajax')
            .then(response => response.json())
            .then(data => {
                if (data.link) {
                    tokenLinkInput.value = data.link;
                }
            })
            .catch(error => console.error('Erreur:', error));
    });
    
    copyBtn.addEventListener("click", function() {
        tokenLinkInput.select();
        document.execCommand("copy");
        copyBtn.textContent = "Copié!";
        setTimeout(() => {
            copyBtn.textContent = "Copier";
        }, 2000);
    });
    
    // Pagination variables
    var itemsPerPage = 10;
    var currentPage = 1;
    var summaryRows = Array.from(document.querySelectorAll('.summary-row'));
    var filteredRows = summaryRows; // Initially, no filter is applied
    var totalPages = Math.ceil(filteredRows.length / itemsPerPage);

    function showPage(page) {
        currentPage = page;
        filteredRows.forEach(function(row, index) {
            if (index >= (page - 1) * itemsPerPage && index < page * itemsPerPage) {
                row.style.display = "";
            } else {
                row.style.display = "none";
                // Also hide the corresponding details row if expanded
                var toggleBtn = row.querySelector('.toggle-details');
                if (toggleBtn) {
                    var id = toggleBtn.getAttribute("data-id");
                    var detailsRow = document.getElementById('details-' + id);
                    if (detailsRow) {
                        detailsRow.style.display = "none";
                        toggleBtn.textContent = "Afficher plus";
                    }
                }
            }
        });
        document.getElementById("prev-page").disabled = (currentPage === 1);
        document.getElementById("next-page").disabled = (currentPage === totalPages || totalPages === 0);
        document.getElementById("page-info").textContent = "Page " + currentPage;
    }

    showPage(1);

    document.getElementById("prev-page").addEventListener("click", function() {
        if (currentPage > 1) {
            showPage(currentPage - 1);
        }
    });
    
    document.getElementById("next-page").addEventListener("click", function() {
        if (currentPage < totalPages) {
            showPage(currentPage + 1);
        }
    });
    
    // Search functionality to filter rows
    var searchInput = document.getElementById("table-search");
    searchInput.addEventListener("keyup", function() {
        var filter = searchInput.value.toLowerCase();
        filteredRows = summaryRows.filter(function(row) {
            var nom = row.children[1].textContent.toLowerCase();
            var prenom = row.children[2].textContent.toLowerCase();
            var telephone = row.children[3].textContent.toLowerCase();
            var email = row.children[4].textContent.toLowerCase();
            if (nom.includes(filter) || prenom.includes(filter) || telephone.includes(filter) || email.includes(filter)) {
                row.style.display = "";
                return true;
            } else {
                row.style.display = "none";
                // Hide associated details row
                var id = row.querySelector(".toggle-details").getAttribute("data-id");
                var detailsRow = document.getElementById('details-' + id);
                if(detailsRow) {
                    detailsRow.style.display = "none";
                }
                return false;
            }
        });
        totalPages = Math.ceil(filteredRows.length / itemsPerPage);
        showPage(1);
    });
    
    // Toggle details for each row
    var toggleButtons = document.querySelectorAll('.toggle-details');
    toggleButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var id = btn.getAttribute('data-id');
            var detailsRow = document.getElementById('details-' + id);
            if (detailsRow.style.display === 'table-row') {
                detailsRow.style.display = 'none';
                btn.textContent = 'Afficher plus';
            } else {
                detailsRow.style.display = 'table-row';
                btn.textContent = 'Masquer';
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    var nationaliteSelect = document.getElementById('nationalite');
    var autreFields = document.getElementById('autre-nationalite-fields');
    nationaliteSelect.addEventListener('change', function() {
      if (nationaliteSelect.value === 'Autre') {
        autreFields.style.display = 'block';
      } else {
        autreFields.style.display = 'none';
      }
    });
  });
  