// ============================================
// 🔒 PROTECCIÓN: Verificar sesión activa
// ============================================
if (sessionStorage.getItem('userLoggedIn') !== 'true') {
    window.location.href = 'index.html';
}

// ============================================
// 🎯 VARIABLES GLOBALES
// ============================================
let selectedAppliance = null;

// ============================================
// 🏠 SELECCIÓN DE ELECTRODOMÉSTICO
// ============================================
document.querySelectorAll('.btn-appliance').forEach(function(button) {
    button.addEventListener('click', function() {
        document.querySelectorAll('.btn-appliance').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        selectedAppliance = this.dataset.type;
        document.getElementById('applianceSelected').textContent = '✅ Seleccionó: ' + selectedAppliance;
    });
});

// ============================================
// 📤 ENVÍO DEL FORMULARIO
// ============================================
document.getElementById('diagnosticForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    console.log('📤 Formulario enviado');
    
    if (!selectedAppliance) {
        alert('⚠️ Por favor, primero seleccione el tipo de electrodoméstico');
        return;
    }
    
    const brand = document.getElementById('brand').value.trim();
    const model = document.getElementById('model').value.trim();
    const symptom = document.getElementById('symptom').value.trim();
    
    console.log('📋 Datos:', { appliance: selectedAppliance, brand, model, symptom });
    
    const resultSection = document.getElementById('resultSection');
    const resultContent = document.getElementById('resultContent');
    
    resultSection.style.display = 'block';
    resultContent.innerHTML = '<div class="loading"><div class="spinner"></div><p>🔍 Analizando el problema...</p></div>';
    
    resultSection.scrollIntoView({ behavior: 'smooth' });
    
    try {
        console.log('🔨 Construyendo prompt...');
        const prompt = buildDiagnosticPrompt(selectedAppliance, brand, model, symptom);
        console.log('📝 Prompt (primeros 200 chars):', prompt.substring(0, 200));
        
        console.log('🤖 Llamando al proxy...');
        const response = await callGroqAPI(prompt);
        
        console.log('✅ Respuesta recibida, formateando...');
        const html = formatResponse(response);
        console.log('📄 HTML generado (primeros 200 chars):', html.substring(0, 200));
        
        resultContent.innerHTML = html;
        console.log('✅ Resultado mostrado en pantalla');
        
    } catch (error) {
        console.error('🚨 Error en el submit:', error);
        resultContent.innerHTML = '<div style="color:#e74c3c;text-align:center;padding:20px;"><p><strong>❌ Error:</strong> ' + error.message + '</p></div>';
    }
});

// ============================================
// 📝 CONSTRUIR PROMPT DE DIAGNÓSTICO
// ============================================
function buildDiagnosticPrompt(appliance, brand, model, symptom) {
    return `Eres un asistente técnico experto en reparación de electrodomésticos. 
Un técnico reparador necesita tu orientación para diagnosticar un problema.

ELECTRODOMÉSTICO: ${appliance}
MARCA: ${brand}
MODELO: ${model}
SÍNTOMA: ${symptom}

INSTRUCCIONES PARA TU RESPUESTA:
1. Saluda cordialmente al colega técnico
2. Proporciona un diagnóstico paso a paso lógico y ordenado
3. Incluye verificaciones de seguridad eléctrica
4. Sugiere las causas más probables primero
5. Explica cómo verificar cada componente
6. Usa lenguaje técnico pero claro
7. Sé específico con mediciones y valores esperados
8. Incluye consejos prácticos de reparación
9. Máximo 400 palabras
10. Responde en español
11. Si no estás seguro, dilo claramente
12. Al final, agrega un "💡 Tip del Experto" con una recomendación práctica`;
}

// ============================================
// 🤖 LLAMAR AL PROXY PHP (api.php)
// ============================================
async function callGroqAPI(prompt) {
    console.log('🔍 Iniciando llamada al proxy...');
    
    try {
        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                prompt: prompt
            })
        });
        
        console.log('📥 Respuesta recibida. Status:', response.status);
        
        if (!response.ok) {
            const errorData = await response.json();
            console.error('❌ Error del proxy:', errorData);
            throw new Error('Error del servidor: ' + (errorData.error || 'Código ' + response.status));
        }
        
        const data = await response.json();
        console.log('✅ Datos recibidos del proxy');
        
        if (data.success && data.text) {
            console.log('📝 Texto extraído (primeros 100 chars):', data.text.substring(0, 100));
            return data.text;
        }
        
        throw new Error('Respuesta inesperada del servidor');
        
    } catch (error) {
        console.error('🚨 Error en callGroqAPI:', error);
        throw error;
    }
}

// ============================================
// 📄 FORMATEAR RESPUESTA EN HTML
// ============================================
function formatResponse(text) {
    let html = '';
    const lines = text.split('\n');
    let inOl = false, inUl = false;
    
    for (let line of lines) {
        line = line.trim();
        if (!line) {
            if (inOl) { html += '</ol>'; inOl = false; }
            if (inUl) { html += '</ul>'; inUl = false; }
            continue;
        }
        
        if (line.startsWith('### ') || line.startsWith('## ') || line.startsWith('# ')) {
            if (inOl) { html += '</ol>'; inOl = false; }
            if (inUl) { html += '</ul>'; inUl = false; }
            html += '<h3>' + line.replace(/^#+\s/, '') + '</h3>';
        } else if (/^\d+\.\s/.test(line)) {
            if (inUl) { html += '</ul>'; inUl = false; }
            if (!inOl) { html += '<ol>'; inOl = true; }
            html += '<li>' + line.replace(/^\d+\.\s/, '') + '</li>';
        } else if (/^[-*•]\s/.test(line)) {
            if (inOl) { html += '</ol>'; inOl = false; }
            if (!inUl) { html += '<ul>'; inUl = true; }
            html += '<li>' + line.replace(/^[-*•]\s/, '') + '</li>';
        } else {
            if (inOl) { html += '</ol>'; inOl = false; }
            if (inUl) { html += '</ul>'; inUl = false; }
            html += '<p>' + line + '</p>';
        }
    }
    if (inOl) html += '</ol>';
    if (inUl) html += '</ul>';
    return html;
}

// ============================================
// 🔄 BOTÓN NUEVA BÚSQUEDA
// ============================================
document.getElementById('newSearchBtn').addEventListener('click', function() {
    document.getElementById('diagnosticForm').reset();
    document.getElementById('resultSection').style.display = 'none';
    document.getElementById('applianceSelected').textContent = '';
    document.querySelectorAll('.btn-appliance').forEach(btn => btn.classList.remove('active'));
    selectedAppliance = null;
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// ============================================
// 🚪 BOTÓN CERRAR SESIÓN
// ============================================
document.getElementById('logoutBtn').addEventListener('click', function() {
    if (confirm('¿Está seguro que desea salir?')) {
        sessionStorage.removeItem('userLoggedIn');
        sessionStorage.removeItem('currentUser');
        window.location.href = 'index.html';
    }
});

// ============================================
// 🔊 LECTOR DE VOZ
// ============================================
let speechUtterance = null;
let isSpeaking = false;
let spanishVoice = null;

const btnVoice = document.getElementById('btnVoice');
const voiceLabel = document.getElementById('voiceLabel');

function loadSpanishVoice() {
    const voices = speechSynthesis.getVoices();
    if (voices.length === 0) return;
    
    spanishVoice = voices.find(v => v.lang.startsWith('es-MX')) ||
                   voices.find(v => v.lang.startsWith('es-ES')) ||
                   voices.find(v => v.lang.startsWith('es-US')) ||
                   voices.find(v => v.lang.startsWith('es')) ||
                   voices[0];
}

if (speechSynthesis.onvoiceschanged !== undefined) {
    speechSynthesis.onvoiceschanged = loadSpanishVoice;
}
setTimeout(loadSpanishVoice, 500);

function getTextFromHTML(html) {
    const temp = document.createElement('div');
    temp.innerHTML = html;
    let text = temp.textContent || temp.innerText || '';
    text = text.replace(/\s+/g, ' ').trim();
    if (text.length > 5000) {
        text = text.substring(0, 5000) + '... Fin del diagnóstico.';
    }
    return text;
}

function resetVoiceButton() {
    isSpeaking = false;
    btnVoice.classList.remove('playing');
    btnVoice.querySelector('.voice-icon-simple').textContent = '🔊';
    voiceLabel.textContent = 'Escuchar diagnóstico';
}

btnVoice.addEventListener('click', function() {
    if (!('speechSynthesis' in window)) {
        alert('Tu navegador no soporta lectura de voz.');
        return;
    }
    
    if (!isSpeaking) {
        const resultContent = document.getElementById('resultContent');
        const text = getTextFromHTML(resultContent.innerHTML);
        
        if (!text || text.trim() === '') {
            alert('No hay texto para leer.');
            return;
        }
        
        speechSynthesis.cancel();
        speechUtterance = new SpeechSynthesisUtterance(text);
        
        if (spanishVoice) {
            speechUtterance.voice = spanishVoice;
        }
        
        speechUtterance.rate = 0.95;
        speechUtterance.pitch = 1;
        speechUtterance.volume = 1;
        speechUtterance.lang = 'es-ES';
        
        speechUtterance.onstart = function() {
            isSpeaking = true;
            btnVoice.classList.add('playing');
            btnVoice.querySelector('.voice-icon-simple').textContent = '⏸️';
            voiceLabel.textContent = 'Pausar';
        };
        
        speechUtterance.onend = function() {
            resetVoiceButton();
        };
        
        speechUtterance.onerror = function(event) {
            if (event.error !== 'canceled') {
                console.error('Error de voz:', event);
            }
            resetVoiceButton();
        };
        
        speechSynthesis.speak(speechUtterance);
        
    } else if (speechSynthesis.paused) {
        speechSynthesis.resume();
        btnVoice.querySelector('.voice-icon-simple').textContent = '⏸️';
        voiceLabel.textContent = 'Pausar';
        
    } else {
        speechSynthesis.pause();
        btnVoice.querySelector('.voice-icon-simple').textContent = '▶️';
        voiceLabel.textContent = 'Reanudar';
    }
});

document.getElementById('newSearchBtn').addEventListener('click', function() {
    speechSynthesis.cancel();
    resetVoiceButton();
});

window.addEventListener('beforeunload', function() {
    speechSynthesis.cancel();
});

setInterval(() => {
    if (isSpeaking && speechSynthesis.speaking && !speechSynthesis.paused) {
        speechSynthesis.pause();
        speechSynthesis.resume();
    }
}, 10000);