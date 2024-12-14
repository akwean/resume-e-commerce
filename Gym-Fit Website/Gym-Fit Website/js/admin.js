// JavaScript to toggle the dropdown box
function toggleDropdown() {
  const dropdownBox = document.getElementById("dropdownBox");
  dropdownBox.classList.toggle("active");
}

// JavaScript for draggable functionality (optional, if needed for other parts of the system)
const boxes = document.querySelectorAll(".draggable-box");

boxes.forEach((box) => {
  let isDragging = false;
  let offsetX, offsetY;

  box.addEventListener("mousedown", (e) => {
    isDragging = true;
    offsetX = e.clientX - box.offsetLeft;
    offsetY = e.clientY - box.offsetTop;
    box.style.cursor = "grabbing";
  });

  document.addEventListener("mousemove", (e) => {
    if (isDragging) {
      box.style.left = `${e.clientX - offsetX}px`;
      box.style.top = `${e.clientY - offsetY}px`;
    }
  });

  document.addEventListener("mouseup", () => {
    isDragging = false;
    box.style.cursor = "grab";
  });
});
