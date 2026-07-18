// Tab switching logic
function switchTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.sidebar-btn').forEach(el => el.classList.remove('active'));
    
    document.getElementById('tab-' + tabId).classList.add('active');
    document.getElementById('btn-' + tabId).classList.add('active');
}

let logInterval;

// Interacting with Python backend
async function startServers() {
    if (window.pywebview) {
        const res = await pywebview.api.start_servers();
        if(res.success) {
            document.getElementById('btn-start').classList.add('hidden');
            document.getElementById('btn-stop').classList.remove('hidden');
            logInterval = setInterval(updateLogs, 1000);
        } else {
            alert(res.message);
        }
    }
}

async function stopServers() {
    if (window.pywebview) {
        await pywebview.api.stop_servers();
        document.getElementById('btn-start').classList.remove('hidden');
        document.getElementById('btn-stop').classList.add('hidden');
        if(logInterval) clearInterval(logInterval);
        updateLogs();
    }
}

async function updateLogs() {
    if (window.pywebview) {
        const logs = await pywebview.api.get_logs();
        const container = document.getElementById('logs-container');
        container.innerText = logs || "Ready.";
        container.scrollTop = container.scrollHeight;
    }
}

function updateInstallProgress(msg) {
    const msgBox = document.getElementById('install-message');
    if (msg) {
        msgBox.innerHTML = msg;
        msgBox.style.display = "block";
    }
}

async function runInstaller() {
    if (window.pywebview) {
        const msgBox = document.getElementById('install-message');
        msgBox.className = "mb-6 p-4 rounded-lg text-sm bg-blue-500/20 text-blue-300 border border-blue-500/50";
        msgBox.innerHTML = "Installing and initializing... Please wait.";
        msgBox.style.display = "block";
        
        document.getElementById('btn-run-install').disabled = true;
        
        const result = await pywebview.api.run_system_install();
        
        if(result.success) {
            msgBox.className = "mb-6 p-4 rounded-lg text-sm bg-green-500/20 text-green-300 border border-green-500/50";
            msgBox.innerHTML = result.message;
        } else {
            msgBox.className = "mb-6 p-4 rounded-lg text-sm bg-red-500/20 text-red-300 border border-red-500/50";
            msgBox.innerHTML = result.message;
        }
        document.getElementById('btn-run-install').disabled = false;
    }
}

async function saveDatabase() {
    if (window.pywebview) {
        const dbName = document.getElementById('db_name').value;
        const dbUser = document.getElementById('db_user').value;
        const dbPass = document.getElementById('db_pass').value;
        
        const btn = event ? event.target : document.querySelector('button[onclick="saveDatabase()"]');
        const originalText = btn.innerText;
        btn.innerText = "Configuring...";
        btn.disabled = true;

        const res = await pywebview.api.configure_database(dbName, dbUser, dbPass);
        
        btn.innerText = originalText;
        btn.disabled = false;
        
        if (res.success) {
            alert('Database configured successfully!');
        } else {
            alert('Error: ' + res.message);
        }
    }
}

async function runMigrations() {
    if (window.pywebview) {
        await pywebview.api.run_migrations();
        alert('Migrations & Seed started in background.');
    }
}

async function saveEnv(key, value) {
    if (window.pywebview) {
        await pywebview.api.update_env(key, value);
        alert(key + ' saved!');
    }
}

async function toggleStartup(enabled) {
    if (window.pywebview) {
        await pywebview.api.toggle_startup(enabled);
    }
}

async function runMaintenance() {
    if (window.pywebview) {
        const res = await pywebview.api.run_maintenance();
        alert(res.message);
    }
}

async function resetSystem() {
    if (window.pywebview) {
        const res = await pywebview.api.reset_system();
        alert(res.message);
        document.getElementById('db_name').value = '';
        document.getElementById('db_user').value = '';
        document.getElementById('db_pass').value = '';
    }
}

async function startPhpMyAdmin() {
    if (window.pywebview) {
        const btn = event ? event.target : null;
        if(btn) {
            btn.disabled = true;
            btn.innerText = "Starting...";
        }
        const res = await pywebview.api.start_phpmyadmin();
        if(btn) {
            btn.disabled = false;
            btn.innerText = "Open phpMyAdmin";
        }
        if(!res.success) {
            alert(res.message);
        }
    }
}

// Update Loop for Status
async function updateStatus() {
    if (window.pywebview && pywebview.api) {
        try {
            const status = await pywebview.api.get_server_status();
            
            // MySQL
            document.getElementById('status-mysql').innerText = status.mysql ? "Online" : "Offline";
            document.getElementById('status-mysql').className = status.mysql ? "text-green-400 text-sm font-medium bg-green-400/10 px-3 py-1 rounded-full" : "text-red-400 text-sm font-medium bg-red-400/10 px-3 py-1 rounded-full";
            document.getElementById('indicator-mysql').className = status.mysql ? "w-3 h-3 rounded-full bg-green-500 shadow-[0_0_8px_#22c55e]" : "w-3 h-3 rounded-full bg-red-500 animate-pulse";
            
            // Hide install button if mysql is running to prevent double config
            const installBtn = document.getElementById('btn-run-install');
            if (installBtn) {
                installBtn.style.display = status.mysql ? 'none' : 'block';
            }
            
            // Laravel
            document.getElementById('status-laravel').innerText = status.laravel ? "Online" : "Offline";
            document.getElementById('status-laravel').className = status.laravel ? "text-green-400 text-sm font-medium bg-green-400/10 px-3 py-1 rounded-full" : "text-red-400 text-sm font-medium bg-red-400/10 px-3 py-1 rounded-full";
            document.getElementById('indicator-laravel').className = status.laravel ? "w-3 h-3 rounded-full bg-green-500 shadow-[0_0_8px_#22c55e]" : "w-3 h-3 rounded-full bg-red-500 animate-pulse";
            
            // Reverb
            document.getElementById('status-reverb').innerText = status.reverb ? "Online" : "Offline";
            document.getElementById('status-reverb').className = status.reverb ? "text-green-400 text-sm font-medium bg-green-400/10 px-3 py-1 rounded-full" : "text-red-400 text-sm font-medium bg-red-400/10 px-3 py-1 rounded-full";
            document.getElementById('indicator-reverb').className = status.reverb ? "w-3 h-3 rounded-full bg-green-500 shadow-[0_0_8px_#22c55e]" : "w-3 h-3 rounded-full bg-red-500 animate-pulse";
            
            // Toggle Start/Stop buttons
            const isRunning = status.mysql || status.laravel || status.reverb;
            const btnStart = document.getElementById('btn-start');
            const btnStop = document.getElementById('btn-stop');
            if (btnStart && btnStop) {
                if (isRunning) {
                    btnStart.classList.add('hidden');
                    btnStop.classList.remove('hidden');
                } else {
                    btnStart.classList.remove('hidden');
                    btnStop.classList.add('hidden');
                }
            }
            
        } catch (e) {}
    }
    setTimeout(updateStatus, 3000);
}

// Initial Data Load
window.addEventListener('pywebviewready', async function() {
    updateStatus();
    
    // Load Env data
    document.getElementById('db_name').value = await pywebview.api.get_env('DB_DATABASE') || '';
    document.getElementById('db_user').value = await pywebview.api.get_env('DB_USERNAME') || '';
    document.getElementById('db_pass').value = await pywebview.api.get_env('DB_PASSWORD') || '';
    document.getElementById('ai_key').value = await pywebview.api.get_env('GEMINI_API_KEY') || '';
    
    // Load Installer Status
    const instStatus = await pywebview.api.get_install_status();
    
    const phpEl = document.getElementById('php-install-status');
    const mysqlEl = document.getElementById('mysql-install-status');
    
    if (instStatus.php_global) {
        phpEl.innerHTML = "✅ Global PHP Detected";
        phpEl.className = "flex items-center gap-2 text-green-400 font-medium";
    } else if (instStatus.php_local) {
        phpEl.innerHTML = "✅ Portable PHP Ready";
        phpEl.className = "flex items-center gap-2 text-green-400 font-medium";
    } else {
        phpEl.innerHTML = "❌ PHP Not Found (Global or 'php' folder)";
        phpEl.className = "flex items-center gap-2 text-red-400 font-medium text-sm";
    }
    
    if (instStatus.mysql_global) {
        mysqlEl.innerHTML = "✅ Global MySQL Detected";
        mysqlEl.className = "flex items-center gap-2 text-green-400 font-medium";
    } else if (instStatus.mysql_local) {
        mysqlEl.innerHTML = "✅ Portable MySQL Ready";
        mysqlEl.className = "flex items-center gap-2 text-green-400 font-medium";
    } else {
        mysqlEl.innerHTML = "❌ MySQL Not Found (Global or 'mysql' folder)";
        mysqlEl.className = "flex items-center gap-2 text-red-400 font-medium text-sm";
    }
    
    // Change Button Text if both global
    if (instStatus.php_global && instStatus.mysql_global) {
        document.getElementById('btn-run-install').innerHTML = "Initialize Database Only";
    }
    
    // Startup toggle state
    document.getElementById('startup_toggle').checked = await pywebview.api.get_startup_status();
    
    // Check for Updates
    fetch('https://api.github.com/repos/AvexiaSolutions/Empire_Hardware_pos/releases/latest')
        .then(res => res.json())
        .then(data => {
            if (data.tag_name) {
                document.getElementById('update-panel').classList.remove('hidden');
                document.getElementById('update-badge').classList.remove('hidden');
                document.getElementById('update-link').href = data.html_url;
            }
        }).catch(e => console.log('Update check failed', e));
});
