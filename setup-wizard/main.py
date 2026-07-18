import os
import sys
import socket
import subprocess
import threading
import winreg
import ctypes
import time
import shutil
import urllib.request
import zipfile
import webview

def resource_path(relative_path):
    try:
        base_path = sys._MEIPASS
    except Exception:
        base_path = os.path.abspath(".")
    return os.path.join(base_path, relative_path)

class Api:
    def __init__(self, window=None):
        self.window = window
        try:
            self.root_path = os.path.dirname(os.path.dirname(os.path.dirname(sys.executable))) if getattr(sys, 'frozen', False) else os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
        except:
            self.root_path = os.getcwd()
            
        self.env_path = os.path.join(self.root_path, ".env")
        self.php_path = os.path.join(self.root_path, "php")
        self.mysql_path = os.path.join(self.root_path, "mysql")
        
        self.processes = {}
        self.log_lines = []
        self.php_bin = None
        self.mysql_bin = None
        self.mysqld_bin = None
        
        # Cache install status in background to prevent UI freeze
        self.install_status = {
            'php_global': False, 'php_local': False,
            'mysql_global': False, 'mysql_local': False,
            'ready': False
        }
        threading.Thread(target=self._init_install_status, daemon=True).start()

    def _init_install_status(self):
        self.install_status['php_global'] = shutil.which('php') is not None
        self.install_status['php_local'] = os.path.exists(os.path.join(self.php_path, "php.exe"))
        self.install_status['mysql_global'] = shutil.which('mysqld') is not None
        self.install_status['mysql_local'] = os.path.exists(os.path.join(self.mysql_path, "bin", "mysqld.exe"))
        self.install_status['ready'] = True

    def _find_php(self):
        if shutil.which("php"): return shutil.which("php")
        local_php = os.path.join(self.php_path, "php.exe")
        return local_php if os.path.exists(local_php) else None

    def _find_mysql(self):
        mysql = shutil.which("mysql")
        mysqld = shutil.which("mysqld")
        local_mysql = os.path.join(self.mysql_path, "bin", "mysql.exe")
        local_mysqld = os.path.join(self.mysql_path, "bin", "mysqld.exe")
        
        if not mysql and os.path.exists(local_mysql): mysql = local_mysql
        if not mysqld and os.path.exists(local_mysqld): mysqld = local_mysqld
        return mysql, mysqld

    def log(self, text):
        self.log_lines.append(text)
        if len(self.log_lines) > 200:
            self.log_lines.pop(0)

    def _reader(self, pipe, prefix):
        for line in iter(pipe.readline, b''):
            try:
                decoded = line.decode('utf-8', errors='replace').strip()
                if decoded:
                    self.log(f"[{prefix}] {decoded}")
            except:
                pass

    def check_port(self, port):
        with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
            s.settimeout(0.05)
            return s.connect_ex(('127.0.0.1', port)) == 0

    def get_server_status(self):
        return {
            'mysql': self.check_port(3306),
            'laravel': self.check_port(8000),
            'reverb': self.check_port(8080)
        }

    def get_logs(self):
        return "\n".join(self.log_lines)

    def start_servers(self):
        self.log("Starting POS System...")
        self.php_bin = self._find_php()
        self.mysql_bin, self.mysqld_bin = self._find_mysql()
        
        if not self.php_bin or not self.mysqld_bin:
            return {"success": False, "message": "PHP or MySQL missing. Please install from System Installer tab first."}

        if not self.check_port(3306) and 'mysql' not in self.processes:
            self.log("Starting MySQL Server...")
            p = subprocess.Popen([self.mysqld_bin, "--console"], stdout=subprocess.PIPE, stderr=subprocess.STDOUT, creationflags=subprocess.CREATE_NO_WINDOW)
            self.processes['mysql'] = p
            threading.Thread(target=self._reader, args=(p.stdout, "MYSQL"), daemon=True).start()
            
        if not self.check_port(8000) and 'web' not in self.processes:
            self.log("Starting Laravel Web Server...")
            p1 = subprocess.Popen([self.php_bin, "artisan", "serve", "--host=127.0.0.1", "--port=8000"], cwd=self.root_path, stdout=subprocess.PIPE, stderr=subprocess.STDOUT, creationflags=subprocess.CREATE_NO_WINDOW)
            self.processes['web'] = p1
            threading.Thread(target=self._reader, args=(p1.stdout, "WEB"), daemon=True).start()
        
        if not self.check_port(8080) and 'socket' not in self.processes:
            self.log("Starting WebSocket Server...")
            p2 = subprocess.Popen([self.php_bin, "artisan", "reverb:start"], cwd=self.root_path, stdout=subprocess.PIPE, stderr=subprocess.STDOUT, creationflags=subprocess.CREATE_NO_WINDOW)
            self.processes['socket'] = p2
            threading.Thread(target=self._reader, args=(p2.stdout, "SOCKET"), daemon=True).start()
        
        if 'queue' not in self.processes:
            self.log("Starting Queue Worker...")
            p3 = subprocess.Popen([self.php_bin, "artisan", "queue:work"], cwd=self.root_path, stdout=subprocess.PIPE, stderr=subprocess.STDOUT, creationflags=subprocess.CREATE_NO_WINDOW)
            self.processes['queue'] = p3
            threading.Thread(target=self._reader, args=(p3.stdout, "QUEUE"), daemon=True).start()
        
        return {"success": True, "message": "Servers Started."}

    def stop_servers(self):
        self.log("Stopping servers...")
        for name, p in self.processes.items():
            try:
                p.kill()
            except:
                pass
        self.processes.clear()
        
        subprocess.run(["taskkill", "/F", "/IM", "php.exe"], creationflags=subprocess.CREATE_NO_WINDOW, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
        
        self.mysql_bin, _ = self._find_mysql()
        if self.mysql_bin and self.check_port(3306):
            db_user = self.get_env('DB_USERNAME') or 'root'
            db_pass = self.get_env('DB_PASSWORD') or ''
            cmd = [self.mysql_bin, "-u", db_user]
            if db_pass: cmd.append(f"-p{db_pass}")
            cmd.extend(["-e", "SHUTDOWN"])
            subprocess.run(cmd, creationflags=subprocess.CREATE_NO_WINDOW, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
            
        self.log("All services stopped.")
        return {"success": True}

    def get_env(self, key):
        if not os.path.exists(self.env_path): return ""
        with open(self.env_path, 'r', encoding='utf-8') as f:
            for line in f:
                if line.startswith(f"{key}="):
                    return line.strip().split('=', 1)[1]
        return ""

    def update_env(self, key, value):
        if not os.path.exists(self.env_path): 
            with open(self.env_path, 'w', encoding='utf-8') as f:
                f.write(f"{key}={value}\n")
            return
            
        with open(self.env_path, 'r', encoding='utf-8') as f:
            lines = f.readlines()
            
        found = False
        for i, line in enumerate(lines):
            if line.startswith(f"{key}="):
                lines[i] = f"{key}={value}\n"
                found = True
                break
                
        if not found:
            lines.append(f"{key}={value}\n")
            
        with open(self.env_path, 'w', encoding='utf-8') as f:
            f.writelines(lines)
            
    def configure_database(self, db_name, db_user, db_pass):
        self.mysql_bin, self.mysqld_bin = self._find_mysql()
        if not self.mysql_bin:
            return {"success": False, "message": "MySQL is not installed."}
            
        proc = None
        if not self.check_port(3306):
            self.log("Temporarily starting MySQL for configuration...")
            proc = subprocess.Popen([self.mysqld_bin, "--console"], creationflags=subprocess.CREATE_NO_WINDOW, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
            for _ in range(15):
                if self.check_port(3306): break
                time.sleep(1)
            
        commands = f"CREATE DATABASE IF NOT EXISTS `{db_name}`;\n"
        if db_user.lower() != 'root':
            commands += f"CREATE USER IF NOT EXISTS '{db_user}'@'localhost' IDENTIFIED BY '{db_pass}';\n"
            commands += f"ALTER USER '{db_user}'@'localhost' IDENTIFIED BY '{db_pass}';\n"
            commands += f"GRANT ALL PRIVILEGES ON `{db_name}`.* TO '{db_user}'@'localhost';\n"
        else:
            commands += f"ALTER USER 'root'@'localhost' IDENTIFIED BY '{db_pass}';\n"
        commands += "FLUSH PRIVILEGES;\n"
        
        current_user = self.get_env('DB_USERNAME') or 'root'
        current_pass = self.get_env('DB_PASSWORD') or ''
        
        cmd = [self.mysql_bin, "-u", current_user]
        if current_pass:
            cmd.append(f"-p{current_pass}")
            
        self.log(f"Configuring Database '{db_name}' and user '{db_user}'...")
        result = subprocess.run(cmd, input=commands.encode('utf-8'), stdout=subprocess.PIPE, stderr=subprocess.PIPE, creationflags=subprocess.CREATE_NO_WINDOW)
        
        if result.returncode != 0:
            if current_user != 'root' or current_pass != '':
                fallback_cmd = [self.mysql_bin, "-u", "root"]
                res2 = subprocess.run(fallback_cmd, input=commands.encode('utf-8'), stdout=subprocess.PIPE, stderr=subprocess.PIPE, creationflags=subprocess.CREATE_NO_WINDOW)
                if res2.returncode != 0:
                    return {"success": False, "message": f"Database config failed: {res2.stderr.decode('utf-8', errors='replace')}"}
            else:
                return {"success": False, "message": f"Database config failed: {result.stderr.decode('utf-8', errors='replace')}"}
                
        self.update_env('DB_DATABASE', db_name)
        self.update_env('DB_USERNAME', db_user)
        self.update_env('DB_PASSWORD', db_pass)
        
        if proc:
            stop_cmd = [self.mysql_bin, "-u", db_user]
            if db_pass: stop_cmd.append(f"-p{db_pass}")
            stop_cmd.extend(["-e", "SHUTDOWN"])
            subprocess.run(stop_cmd, creationflags=subprocess.CREATE_NO_WINDOW)
            try: proc.wait(timeout=5)
            except: proc.kill()
            
        return {"success": True, "message": "Database & Env successfully configured!"}

    def get_install_status(self):
        return self.install_status
        
    def _download_and_extract(self, url, extract_to, msg_prefix):
        if self.window: self.window.evaluate_js(f"updateInstallProgress('{msg_prefix} Downloading (this may take a few minutes)...')")
        zip_path = os.path.join(self.root_path, "temp_dl.zip")
        try:
            req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
            with urllib.request.urlopen(req) as response, open(zip_path, 'wb') as out_file:
                shutil.copyfileobj(response, out_file)
            
            if self.window: self.window.evaluate_js(f"updateInstallProgress('{msg_prefix} Extracting files...')")
            with zipfile.ZipFile(zip_path, 'r') as zip_ref:
                zip_ref.extractall(extract_to)
            os.remove(zip_path)
            return True
        except Exception as e:
            self.log(f"Download failed for {url}: {e}")
            if os.path.exists(zip_path): os.remove(zip_path)
            return False

    def run_system_install(self):
        status = self.get_install_status()
        
        if not (status['php_global'] or status['php_local']):
            php_url = "https://windows.php.net/downloads/releases/archives/php-8.2.12-nts-Win32-vs16-x64.zip" 
            self.log("PHP missing. Initiating download...")
            os.makedirs(self.php_path, exist_ok=True)
            if not self._download_and_extract(php_url, self.php_path, "PHP:"):
                return {"success": False, "message": "Failed to auto-download PHP."}
                
        if not (status['mysql_global'] or status['mysql_local']):
            maria_url = "https://archive.mariadb.org/mariadb-11.1.2/winx64-packages/mariadb-11.1.2-winx64.zip"
            self.log("MySQL/MariaDB missing. Initiating download...")
            temp_mysql_extract = os.path.join(self.root_path, "temp_mysql")
            os.makedirs(temp_mysql_extract, exist_ok=True)
            if not self._download_and_extract(maria_url, temp_mysql_extract, "Database:"):
                return {"success": False, "message": "Failed to auto-download Database."}
            
            extracted_folders = os.listdir(temp_mysql_extract)
            if extracted_folders:
                inner_folder = os.path.join(temp_mysql_extract, extracted_folders[0])
                if os.path.exists(self.mysql_path): shutil.rmtree(self.mysql_path)
                shutil.move(inner_folder, self.mysql_path)
            shutil.rmtree(temp_mysql_extract)

        self.php_bin = self._find_php()
        self.mysql_bin, self.mysqld_bin = self._find_mysql()
        
        paths_to_add = []
        if not status['php_global'] and os.path.exists(self.php_path):
            paths_to_add.append(self.php_path)
        if not status['mysql_global'] and os.path.exists(os.path.join(self.mysql_path, "bin")):
            paths_to_add.append(os.path.join(self.mysql_path, "bin"))

        if paths_to_add:
            try:
                key = winreg.OpenKey(winreg.HKEY_CURRENT_USER, r'Environment', 0, winreg.KEY_READ | winreg.KEY_WRITE)
                current_path, _ = winreg.QueryValueEx(key, 'Path')
                changed = False
                for p in paths_to_add:
                    if p not in current_path:
                        current_path += f";{p}"
                        changed = True
                if changed:
                    winreg.SetValueEx(key, 'Path', 0, winreg.REG_EXPAND_SZ, current_path)
                    HWND_BROADCAST = 0xFFFF
                    WM_SETTINGCHANGE = 0x001A
                    SMTO_ABORTIFHUNG = 0x0002
                    result = ctypes.c_long()
                    ctypes.windll.user32.SendMessageTimeoutW(HWND_BROADCAST, WM_SETTINGCHANGE, 0, u"Environment", SMTO_ABORTIFHUNG, 5000, ctypes.byref(result))
            except Exception as e:
                pass
                
        if self.mysqld_bin:
            data_dir = os.path.join(self.mysql_path, "data")
            if not os.path.exists(data_dir) or not os.listdir(data_dir):
                if self.window: self.window.evaluate_js("updateInstallProgress('Initializing Database data directory...')")
                subprocess.run([self.mysqld_bin, "--initialize-insecure"], creationflags=subprocess.CREATE_NO_WINDOW)

        if self.window: self.window.evaluate_js("updateInstallProgress('')")
        return {"success": True, "message": "Installation and Initialization Complete!"}

    def run_migrations(self):
        self.php_bin = self._find_php()
        if not self.php_bin: return
        self.log("Running migrations & seed...")
        subprocess.Popen([self.php_bin, "artisan", "migrate", "--seed", "--force"], cwd=self.root_path, creationflags=subprocess.CREATE_NO_WINDOW)

    def run_maintenance(self):
        self.php_bin = self._find_php()
        if not self.php_bin: return {"success": False, "message": "PHP not found."}
        self.log("Running system maintenance...")
        commands = [
            [self.php_bin, "artisan", "optimize:clear"],
            [self.php_bin, "artisan", "view:clear"],
            [self.php_bin, "artisan", "route:clear"],
            [self.php_bin, "artisan", "config:clear"]
        ]
        for cmd in commands:
            subprocess.run(cmd, cwd=self.root_path, creationflags=subprocess.CREATE_NO_WINDOW)
        self.log("Maintenance complete.")
        return {"success": True, "message": "Caches cleared successfully."}

    def reset_system(self):
        self.log("Initiating factory reset...")
        self.stop_servers()
        
        db_name = self.get_env('DB_DATABASE')
        if db_name:
            self.mysql_bin, self.mysqld_bin = self._find_mysql()
            if self.mysqld_bin and self.mysql_bin:
                proc = subprocess.Popen([self.mysqld_bin, "--console"], creationflags=subprocess.CREATE_NO_WINDOW, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
                time.sleep(3) 
                db_user = self.get_env('DB_USERNAME') or 'root'
                db_pass = self.get_env('DB_PASSWORD') or ''
                
                cmd = [self.mysql_bin, "-u", db_user]
                if db_pass: cmd.append(f"-p{db_pass}")
                cmd.extend(["-e", f"DROP DATABASE IF EXISTS `{db_name}`;"])
                subprocess.run(cmd, creationflags=subprocess.CREATE_NO_WINDOW)
                
                stop_cmd = [self.mysql_bin, "-u", "root", "-e", "SHUTDOWN"]
                subprocess.run(stop_cmd, creationflags=subprocess.CREATE_NO_WINDOW)
                try: proc.wait(timeout=5)
                except: proc.kill()

        if os.path.exists(self.env_path):
            with open(self.env_path, 'w', encoding='utf-8') as f:
                f.write("")
                
        self.log("System reset complete.")
        return {"success": True, "message": "System has been factory reset."}

    def get_startup_status(self):
        startup_path = os.path.join(os.getenv('APPDATA'), r'Microsoft\Windows\Start Menu\Programs\Startup', 'EmpirePOS.bat')
        return os.path.exists(startup_path)

    def toggle_startup(self, enabled):
        startup_path = os.path.join(os.getenv('APPDATA'), r'Microsoft\Windows\Start Menu\Programs\Startup', 'EmpirePOS.bat')
        vbs_path = os.path.join(self.root_path, "startup.vbs")
        
        if enabled:
            with open(startup_path, "w") as f:
                f.write(f'@echo off\ncd /d "{self.root_path}"\nstart "" "{vbs_path}"\n')
        else:
            if os.path.exists(startup_path):
                os.remove(startup_path)
        return {"success": True, "message": "Startup settings updated."}

    def start_phpmyadmin(self):
        self.php_bin = self._find_php()
        if not self.php_bin: return {"success": False, "message": "PHP not found."}
        
        pma_path = os.path.join(self.root_path, "phpMyAdmin")
        if not os.path.exists(pma_path):
            return {"success": False, "message": "phpMyAdmin folder not found in project directory."}
            
        port = 8081
        if self.check_port(port):
            import webbrowser
            webbrowser.open(f"http://127.0.0.1:{port}")
            return {"success": True, "message": "phpMyAdmin opened in browser."}
            
        self.log("Starting phpMyAdmin server...")
        p = subprocess.Popen([self.php_bin, "-S", f"127.0.0.1:{port}", "-t", pma_path], stdout=subprocess.PIPE, stderr=subprocess.STDOUT, creationflags=subprocess.CREATE_NO_WINDOW)
        self.processes['phpmyadmin'] = p
        threading.Thread(target=self._reader, args=(p.stdout, "PMA"), daemon=True).start()
        
        for _ in range(15):
            if self.check_port(port): break
            time.sleep(0.5)
            
        import webbrowser
        webbrowser.open(f"http://127.0.0.1:{port}")
        return {"success": True, "message": "phpMyAdmin started and opened in browser."}

if __name__ == '__main__':
    api = Api(None)
    html_path = resource_path("ui/index.html")
    if getattr(sys, 'frozen', False):
        os.chdir(sys._MEIPASS)
        
    user32 = ctypes.windll.user32
    screen_w = user32.GetSystemMetrics(0)
    screen_h = user32.GetSystemMetrics(1)
    win_w = 1150
    win_h = 750
    x = (screen_w // 2) - (win_w // 2)
    y = (screen_h // 2) - (win_h // 2)
    
    window = webview.create_window('Empire POS - Advanced Setup Wizard', html_path, js_api=api, width=win_w, height=win_h, x=x, y=y, resizable=False)
    window.expose(api.start_servers, api.stop_servers, api.get_server_status, api.get_logs, api.get_env, api.update_env, api.configure_database, api.get_install_status, api.run_system_install, api.run_migrations, api.run_maintenance, api.reset_system, api.get_startup_status, api.toggle_startup, api.start_phpmyadmin)
    api.window = window
    webview.start()
