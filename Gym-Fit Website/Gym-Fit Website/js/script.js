// Add interactivity or functionality here, if needed.
// Currently no specific functionality is required for this layout.
function logPageLoad() {
  if (
    typeof internalLogger !== "undefined" &&
    typeof internalLogger.log === "function"
  ) {
    const startTime = performance.now();
    window.addEventListener("load", () => {
      try {
        const endTime = performance.now();
        internalLogger.log(
          `Page loaded successfully in ${endTime - startTime} milliseconds.`
        );
      } catch (error) {
        console.error("Error during page load:", error);
      }
    });
  }
}
logPageLoad();

// Scroll-to-Top Button Functionality
const scrollToTopBtn = document.querySelector(".scroll-to-top");

window.addEventListener("scroll", () => {
  if (window.scrollY > 300) {
    scrollToTopBtn.style.display = "block";
  } else {
    scrollToTopBtn.style.display = "none";
  }
});

scrollToTopBtn.addEventListener("click", () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});

