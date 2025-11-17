document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const loginBtn = document.getElementById('loginBtn');
    const loginError = document.getElementById('loginError');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const formData = new FormData(e.target);
    
    // Mostrar spinner y deshabilitar botón
    loginBtn.disabled = true;
    btnText.textContent = 'Iniciando sesión...';
    btnSpinner.classList.remove('hidden');
    loginError.classList.add('hidden');
    
    try {
        const response = await fetch(PUBLIC_PATH + '/auth/login', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Cambiar texto y ocultar spinner
            btnText.textContent = '✓ Redirigiendo...';
            btnSpinner.classList.add('hidden');
            loginBtn.classList.remove('bg-orange-500', 'hover:bg-orange-600');
            loginBtn.classList.add('bg-green-500');
            
            // Redirección INMEDIATA sin setTimeout
            window.location.href = data.redirect;
        } else {
            // Mostrar error
            loginError.textContent = data.message || 'Error en el login';
            loginError.classList.remove('hidden');
            
            // Restaurar botón
            btnSpinner.classList.add('hidden');
            btnText.textContent = 'Iniciar sesión';
            loginBtn.disabled = false;
        }
    } catch (error) {
        console.error('Error:', error);
        loginError.textContent = 'Error al conectar con el servidor';
        loginError.classList.remove('hidden');
        
        // Restaurar botón
        btnSpinner.classList.add('hidden');
        btnText.textContent = 'Iniciar sesión';
        loginBtn.disabled = false;
    }
});