
document.addEventListener("DOMContentLoaded", () => {
  const main = document.querySelector("main");
  if (main) main.classList.add("fade-in");

  document.querySelectorAll("nav a").forEach((link) => {
    link.addEventListener("click", (e) => {
      
      if (!link.classList.contains("active")) {
        if (main) main.classList.add("fade-out");
        setTimeout(() => {
          window.location.href = link.href;
        }, 200);
        e.preventDefault();
      }
    });
  });
});


const toggleBtn = document.createElement("button");
toggleBtn.innerHTML = "🌙";
toggleBtn.className =
  "btn btn-outline-secondary position-fixed bottom-0 end-0 m-4 rounded-circle shadow";
toggleBtn.style.width = "50px";
toggleBtn.style.height = "50px";
toggleBtn.style.fontSize = "20px";
toggleBtn.style.zIndex = "999";
document.body.appendChild(toggleBtn);

if (localStorage.getItem("darkMode") === "true") {
  document.body.classList.add("dark");
  toggleBtn.innerHTML = "☀️";
}
toggleBtn.addEventListener("click", () => {
  const isDark = document.body.classList.toggle("dark");
  toggleBtn.innerHTML = isDark ? "☀️" : "🌙";
  localStorage.setItem("darkMode", isDark);
});
document.addEventListener("keydown", (e) => {
  if (e.key.toLowerCase() === "d") {
    toggleBtn.click();
  }
});
