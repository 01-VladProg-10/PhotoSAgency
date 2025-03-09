document.addEventListener("DOMContentLoaded", () => {
     const textElements = document.querySelectorAll(".text-appear");
     const revealOnScroll = () => {
         textElements.forEach(element => {
             const rect = element.getBoundingClientRect();
             if (rect.top < window.innerHeight - 100) {
                 element.classList.add("visible");
             }
         });
     };
     window.addEventListener("scroll", revealOnScroll);
     revealOnScroll();
 });