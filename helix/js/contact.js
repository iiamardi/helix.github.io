document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const successAlert = document.getElementById('successAlert');
    
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = {
            name: contactForm.elements['name'].value,
            email: contactForm.elements['email'].value,
            phone: contactForm.elements['phone'].value,
            message: contactForm.elements['message'].value
        };
        
        // In a real app, you would send this to your backend
        console.log('Form submitted:', formData);
        
        // Show success message
        successAlert.classList.remove('d-none');
        contactForm.reset();
        
        // Hide success message after 5 seconds
        setTimeout(() => {
            successAlert.classList.add('d-none');
        }, 5000);
    });
    
    // Initialize animations
    animateOnScroll();
});

function animateOnScroll() {
    const animatedSections = document.querySelectorAll('.animated-section');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    animatedSections.forEach(section => {
        observer.observe(section);
    });
}