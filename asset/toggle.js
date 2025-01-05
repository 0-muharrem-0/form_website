function toggleMenu() {
    var navbar = document.getElementById("nav-toggle").nextElementSibling;
    navbar.querySelector("ol").classList.toggle("show");
  }
  
  function toggleMode() {
    document.body.classList.toggle('dark-mode');
  }
  