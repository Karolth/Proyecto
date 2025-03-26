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
  
    const modalTriggers = document.querySelectorAll('[data-modal]');
    
 
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(`${modalId}-modal`);
            
            if (modal) {
                modal.classList.add('active');
            }
        });
    });
    
//    
    const closeButtons = document.querySelectorAll('.close-modal');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal-backdrop');
            modal.classList.remove('active');
        });
    });
    
    
    const modalContents = document.querySelectorAll('.modal-content');
    modalContents.forEach(content => {
        content.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    

    document.getElementById('logout-link').addEventListener('click', function(e) {
        e.preventDefault();
      
        
        // Redireccionar a la página de inicio de sesión
        window.location.href = 'loginEasyCodeIS.html';
    });
});