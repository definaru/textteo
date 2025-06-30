document.addEventListener("DOMContentLoaded", function () {
  /******************************************/
  /* ========== MENU CODE ========== */
  /******************************************/
  const menuIcon = document.getElementById("menu-icon");
  const mobileNav = document.getElementById("mobile-nav");
  const mobileOverlay = document.getElementById("mobile-nav-overlay");
  const closeMenuItems = document.querySelectorAll(".close-menu");
  const body = document.body;

  // Function to open the menu
  function openMenu() {
    console.log("Opening menu...");
    mobileNav.classList.add("active");
    mobileOverlay.classList.add("active");
    body.classList.add("menu-open");
  }

  // Function to close the menu
  function closeMenu() {
    console.log("Closing menu...");
    mobileNav.classList.remove("active");
    mobileOverlay.classList.remove("active");
    body.classList.remove("menu-open");
  }

  // Toggle menu on menu icon click
  menuIcon?.addEventListener("click", function (e) {
    e.stopPropagation();
    console.log("Menu icon clicked.");
    if (mobileNav.classList.contains("active")) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  // Close menu when clicking on the overlay
  mobileOverlay?.addEventListener("click", function () {
    console.log("Overlay clicked.");
    closeMenu();
  });

  // Close menu when clicking on any link inside mobile nav
  closeMenuItems.forEach((item) => {
    item.addEventListener("click", function () {
      console.log("Menu link clicked.");
      closeMenu();
    });
  });

  // Close menu when the ESC key is pressed
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      console.log("ESC pressed.");
      closeMenu();
    }
  });

  /******************************************/
  /* ========== PROBLEM CARDS SCROLL CODE ========== */
  /******************************************/
  const cardsContainer = document.querySelector(".problem-cards");
  const arrowLeft = document.querySelector(".arrow-left");
  const arrowRight = document.querySelector(".arrow-right");

  if (arrowLeft && arrowRight && cardsContainer) {
    arrowLeft.addEventListener("click", () => {
      cardsContainer.scrollBy({ left: -300, behavior: "smooth" });
    });

    arrowRight.addEventListener("click", () => {
      cardsContainer.scrollBy({ left: 300, behavior: "smooth" });
    });
  }

  /******************************************/
  /* ========== REVIEW CAROUSEL CODE ========== */
  /******************************************/
  // 1) Grab references to the carousel elements.
  const reviewTrack = document.querySelector(".review-slider-track");
  const reviewSlides = document.querySelectorAll(".review-slide");
  const reviewArrowLeft = document.querySelector(".arrows-wrapper .arrow-left");
  const reviewArrowRight = document.querySelector(
    ".arrows-wrapper .arrow-right"
  );
  const reviewDots = document.querySelectorAll(".dots-wrapper .dot");

  // 2) Only run if all elements exist.
  if (
    reviewTrack &&
    reviewSlides.length &&
    reviewArrowLeft &&
    reviewArrowRight &&
    reviewDots.length
  ) {
    let currentSlide = 0;
    const totalSlides = reviewSlides.length;

    // Go to a specific slide by index
    function goToSlide(index) {
      currentSlide = index;
      reviewTrack.style.transform = `translateX(-${index * 100}%)`;

      // Update dot "active" class
      reviewDots.forEach((dot, i) => {
        dot.classList.toggle("active", i === index);
      });
    }

    // Arrow: Next
    reviewArrowRight.addEventListener("click", () => {
      let nextSlide = currentSlide + 1;
      // If you want infinite wrap-around:
      if (nextSlide >= totalSlides) nextSlide = 0;
      goToSlide(nextSlide);
    });

    // Arrow: Prev
    reviewArrowLeft.addEventListener("click", () => {
      let prevSlide = currentSlide - 1;
      // If you want infinite wrap-around:
      if (prevSlide < 0) prevSlide = totalSlides - 1;
      goToSlide(prevSlide);
    });

    // Dot clicks
    reviewDots.forEach((dot) => {
      dot.addEventListener("click", () => {
        const slideIndex = parseInt(dot.getAttribute("data-slide"), 10);
        goToSlide(slideIndex);
      });
    });
  }

  /******************************************/
  /* ========== HOW IT WORKS STEPS CODE ========== */
  /******************************************/
  /******************************************/
  /* ========== DESKTOP STEPS CODE ========== */
  /******************************************/
  const desktopSteps = document.querySelectorAll(".row.d-lg-flex .hiw-step");
  const desktopImages = document.querySelectorAll(".row.d-lg-flex .step-image");

  function activateDesktopStep(stepNum) {
    // Highlight correct step
    desktopSteps.forEach((st) => {
      st.classList.remove("active");
      if (st.dataset.step === stepNum) st.classList.add("active");
    });
    // Show matching phone image
    desktopImages.forEach((img) => {
      img.classList.remove("active");
      if (img.dataset.step === stepNum) img.classList.add("active");
    });
  }

  // If any desktop steps exist, default to step 1
  if (desktopSteps.length && desktopImages.length) {
    activateDesktopStep("1");
    desktopSteps.forEach((step) => {
      step.addEventListener("click", () => {
        activateDesktopStep(step.dataset.step);
      });
    });
  }

  /******************************************/
  /* ========== MOBILE CODE ========== */
  /******************************************/
  // 1) Mobile slides + dots
  const mobileSlides = document.querySelectorAll(
    ".mobile-slider-wrapper .mobile-slide"
  );
  const mobileDots = document.querySelectorAll(".mobile-slider-wrapper .dot");

  // 2) Mobile steps
  const mobileSteps = document.querySelectorAll(".mobile-steps .hiw-step");

  function activateMobileSlide(slideNum) {
    // slides
    mobileSlides.forEach((slide) => {
      slide.classList.remove("active");
      if (slide.dataset.slide === slideNum) slide.classList.add("active");
    });
    // dots
    mobileDots.forEach((dot) => {
      dot.classList.remove("active");
      if (dot.dataset.slide === slideNum) dot.classList.add("active");
    });
    // steps
    mobileSteps.forEach((step) => {
      step.classList.remove("active");
      if (step.dataset.step === slideNum) step.classList.add("active");
    });
  }

  // Default mobile to slide 1 if found
  if (mobileSlides.length && mobileDots.length && mobileSteps.length) {
    activateMobileSlide("1");

    // Clicking a dot
    mobileDots.forEach((dot) => {
      dot.addEventListener("click", () => {
        activateMobileSlide(dot.dataset.slide);
      });
    });
    // Clicking a mobile step
    mobileSteps.forEach((step) => {
      step.addEventListener("click", () => {
        activateMobileSlide(step.dataset.step);
      });
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const faqItems = document.querySelectorAll(".faq-item");

  faqItems.forEach((item) => {
    const questionEl = item.querySelector(".faq-question");
    questionEl.addEventListener("click", () => {
      // If you want multiple FAQ items open at once, simply toggle this one:
      item.classList.toggle("open");
    });
  });
});