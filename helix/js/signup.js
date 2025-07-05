document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('signupForm');
    const errorAlert = document.getElementById('errorAlert');
    
    signupForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        try {
            const response = await fetch('http://your-php-backend/signup.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name, email, password })
            });
            
            const data = await response.json();
            
            if (data.success) {
                window.location.href = '/login.html';
            } else {
                showError(data.message || 'Signup failed');
            }
        } catch (err) {
            console.error('Signup error:', err);
            showError('An error occurred. Please try again.');
        }
    });
    
    function showError(message) {
        errorAlert.textContent = message;
        errorAlert.classList.remove('d-none');
    }
});