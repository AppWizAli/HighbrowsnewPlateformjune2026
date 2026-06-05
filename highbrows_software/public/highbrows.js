


// Function for Row 1 (2 cycles right to left, then 2 cycles left to right)
function animateRow1() {
    const row1 = document.querySelector('.animate-from-left');  // Get the row of images
    const images1 = row1.querySelectorAll('img');  // Get all images in the row

    // Clone images to create a seamless loop
    images1.forEach((img) => {
        const clone = img.cloneNode(true);
        row1.appendChild(clone);
    });

    // Calculate total width of all images (including clones)
    const totalWidth1 = row1.scrollWidth / 6;

    // Initial settings
    let position1 = 0;
    let direction1 = 1; // 1 = left to right initially
    let cycleCount1 = 0; // Track how many cycles have passed

    // Function to animate the row
    function animate1() {
        position1 += direction1 * 2;  // Move based on direction (right or left)

        // Apply the movement to the row
        row1.style.transform = `translateX(${position1}px)`;

        // Check if 2 cycles have been completed (each cycle being right-to-left OR left-to-right)
        if (position1 >= totalWidth1 || position1 <= 0) {
            cycleCount1++;  // Increase cycle count by 1 after each cycle

            // Reverse direction after completing 2 cycles
            if (cycleCount1 >= 4) { // 2 cycles forward + 2 cycles backward = 4
                direction1 *= -1;  // Reverse direction
                cycleCount1 = 0;   // Reset cycle count after 4 cycles (2 complete movements)
            }
        }

        // Continuously animate the row
        requestAnimationFrame(animate1);
    }

    // Start the animation
    animate1();
}

// Function for Row 2 (2 cycles right to left, then 2 cycles left to right)
function animateRow2() {
    const row2 = document.querySelector('.animate-from-right');  // Get the row of images
    const images2 = row2.querySelectorAll('img');  // Get all images in the row

    // Clone images to create a seamless loop
    images2.forEach((img) => {
        const clone = img.cloneNode(true);
        row2.appendChild(clone);
    });

    // Calculate total width of all images (including clones)
    const totalWidth2 = row2.scrollWidth / 6;

    // Initial settings
    let position2 = 0;
    let direction2 = -1; // -1 = right to left initially
    let cycleCount2 = 2; // Track how many cycles have passed

    // Function to animate the row
    function animate2() {
        position2 += direction2 * 2;  // Move based on direction (left or right)

        // Apply the movement to the row
        row2.style.transform = `translateX(${position2}px)`;

        // Check if 2 cycles have been completed (each cycle being right-to-left OR left-to-right)
        if (position2 <= -totalWidth2 || position2 >= 0) {
            cycleCount2++;  // Increase cycle count by 1 after each cycle

            // Reverse direction after completing 2 cycles
            if (cycleCount2 >= 4) { // 2 cycles forward + 2 cycles backward = 4
                direction2 *= -1;  // Reverse direction
                cycleCount2 = 0;   // Reset cycle count after 4 cycles (2 complete movements)
            }
        }

        // Continuously animate the row
        requestAnimationFrame(animate2);
    }

    // Start the animation
    animate2();
}

// Start both animations when window loads
window.onload = function () {
    setTimeout(() => {
        animateRow1();  // Start animation for row 1
        animateRow2();  // Start animation for row 2
    }, ); // Small delay for DOM readiness
};

//2nd section end of javascript