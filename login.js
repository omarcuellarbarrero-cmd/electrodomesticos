// ============================================
// 🔐 LÓGICA DE LOGIN CON BASE DE DATOS
// ============================================
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const email = document.getElementById('username').value.trim();
    const pass = document.getElementById('password').value.trim();
    const btn = document.querySelector('.btn-primary');
    
    btn.textContent = 'Verificando...';
    btn.disabled = true;

    try {
        const response = await fetch('auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email, password: pass })
        });
        
        const data = await response.json();
        
        if (data.success) {
            sessionStorage.setItem('userLoggedIn', 'true');
            sessionStorage.setItem('currentUser', email);
            window.location.href = data.redirect;
        } else {
            alert('❌ ' + data.message);
            btn.textContent = 'Ingresar';
            btn.disabled = false;
        }
    } catch (error) {
        alert('❌ Error de conexión con el servidor');
        btn.textContent = 'Ingresar';
        btn.disabled = false;
    }
});