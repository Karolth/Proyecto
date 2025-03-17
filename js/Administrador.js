
let btn = document.querySelector("#btn");
let sidebar = document.querySelector(".sidebar");

btn.onclick = function() {
  sidebar.classList.toggle("active");
}

function registrar(tipo) {
    fetch('../php/Administrador.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `movimiento=${tipo}`
    })
    .then(response => response.text())
    .then(data => alert(data))
    .catch(error => console.error('Error:', error));
}

document.addEventListener('DOMContentLoaded', function() {
  let btn = document.querySelector("#btn");
  let sidebar = document.querySelector(".sidebar");

  if (btn && sidebar) {
      btn.onclick = function() {
          sidebar.classList.toggle("active");
      };
  } else {
      console.error("Sidebar or button element not found.");
  }
});