import "./stimulus_bootstrap.js";
import "./styles/app.css";
import "./bootstrap.bundle.min.js";

document.addEventListener("click", (event) => {
  if (event.target.matches(".menu-btn") || event.target.closest(".menu-btn")) {
    const target = event.target.closest(".menu-btn") || event.target;

    if (target.style.transform === "rotate(180deg)") {
      target.style.transform = "rotate(0deg)";
    } else {
      target.style.transform = "rotate(180deg)";
    }

    const navLinksLabel = document.querySelectorAll(".nav-link span");
    navLinksLabel.forEach((label) => label.classList.toggle("d-none"));
  }
});

const tooltipTriggerList = document.querySelectorAll(
  '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
  (el) => new bootstrap.Tooltip(el)
);
