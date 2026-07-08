<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $users = User::all();
        
        return view('settings.index', compact('settings', 'users'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'logo']);

        // Handle text settings
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $url = '/storage/' . $path;
            
            Setting::updateOrCreate(
                ['key' => 'shop_logo'],
                ['value' => $url]
            );

            // Update manifest.json
            $manifestPath = public_path('manifest.json');
            if (file_exists($manifestPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true);
                if (isset($manifest['icons'])) {
                    $manifest['icons'][0]['src'] = $url;
                    $manifest['icons'][1]['src'] = $url;
                    file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                }
            }
        }

        // Clear settings cache
        \Illuminate\Support\Facades\Cache::forget('settings_all');

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,cashier,manager',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,'.$id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role' => 'required|string|in:admin,cashier,manager',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function updateUserPermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'permissions' => 'nullable|array',
        ]);

        $user->permissions = $request->permissions ?? [];
        $user->save();

        return redirect()->back()->with('success', 'User permissions updated successfully.');
    }

    public function manualBackup()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('app:database-backup');
            return redirect()->back()->with('success', 'Manual backup triggered and queued successfully! Please check your email in a few minutes.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function systemUpdate()
    {
        set_time_limit(300); // 5 minutes

        $repoUrl = 'https://github.com/AvexiaSolutions/Empire_Hardware_pos.git';
        $basePath = base_path();
        
        $outputLogs = [];
        $commands = [];

        // Check if git is initialized
        if (!is_dir($basePath . '/.git')) {
            $commands[] = 'git init';
            $commands[] = 'git remote add origin ' . escapeshellarg($repoUrl);
        }

        $commands[] = 'git fetch origin';
        $commands[] = 'git reset --hard origin/main';
        $commands[] = 'composer install --no-dev --optimize-autoloader';
        $commands[] = 'php artisan migrate --force';
        $commands[] = 'php artisan optimize:clear';

        foreach ($commands as $command) {
            $result = Process::path($basePath)->run($command);
            
            $outputLogs[] = "$ " . $command . "\n" . $result->output() . "\n" . $result->errorOutput();
        }

        Log::info("System Update Output: \n" . implode("\n\n", $outputLogs));

        return redirect()->back()->with('success', 'System successfully updated with the latest source code from GitHub!');
    }
}
