<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - BEM Management System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .login-container {
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .login-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            padding: 3rem 2rem;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
        }
        
        .login-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
            font-size: 0.9rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #f9fafb;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-input::placeholder {
            color: #9ca3af;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
        }
        
        .alert-danger {
            background-color: #fee2e2;
            border-left-color: #ef4444;
            color: #7f1d1d;
        }
        
        .btn-login {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        
        .remember-forgot input[type="checkbox"] {
            width: auto;
            margin-right: 0.5rem;
        }
        
        .remember-forgot label {
            display: flex;
            align-items: center;
            color: #374151;
            cursor: pointer;
        }
        
        .forgot-link {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .forgot-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .error-message {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        
        .input-error {
            border-color: #dc2626 !important;
        }
        
        .loading {
            display: none;
        }
        
        .btn-login.loading {
            opacity: 0.7;
            pointer-events: none;
        }
        
        .btn-login.loading .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.6s linear infinite;
        }
        
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-cube"></i>
                </div>
                <h1 class="login-title">BEM</h1>
                <p class="login-subtitle">Sistem Manajemen Dokumen</p>
            </div>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Login Gagal</strong>
                    <ul style="margin: 0.5rem 0 0 0; list-style: none; padding: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('login') }}" method="POST" novalidate>
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input @error('email') input-error @enderror" 
                        placeholder="masukkan email anda"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input @error('password') input-error @enderror" 
                        placeholder="masukkan password anda"
                        required
                    >
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="remember-forgot">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Ingat saya</span>
                    </label>
                </div>
                
                <button type="submit" class="btn-login">
                    <span class="loading"><i class="fas fa-spinner"></i></span>
                    <span>Masuk</span>
                </button>
            </form>
            
            <div style="margin-top: 2rem; text-align: center; color: #6b7280; font-size: 0.9rem;">
                <p>Hubungi administrator untuk mendaftar akun</p>
            </div>
        </div>
    </div>
    
    <script>
        // Add loading state to form
        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.querySelector('.btn-login');
            btn.classList.add('loading');
        });
    </script>
</body>
</html>
