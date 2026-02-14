<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File ke R2</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .file-upload-wrapper {
            position: relative;
            display: block;
            cursor: pointer;
        }
        
        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 40px 20px;
            border: 2px dashed #667eea;
            border-radius: 5px;
            background: #f8f9ff;
            transition: all 0.3s;
            font-size: 16px;
            color: #667eea;
        }
        
        .file-upload-label:hover {
            background: #f0f2ff;
            border-color: #764ba2;
        }
        
        .file-upload-label.dragover {
            background: #e0e4ff;
            border-color: #764ba2;
        }
        
        #fileInput {
            display: none;
        }
        
        .file-name {
            margin-top: 10px;
            color: #666;
            font-size: 14px;
            text-align: center;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .loader {
            display: none;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .response-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
            display: none;
        }
        
        .response-box h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .response-text {
            background: white;
            padding: 10px;
            border-radius: 3px;
            color: #666;
            font-family: monospace;
            font-size: 12px;
            word-break: break-all;
            max-height: 150px;
            overflow-y: auto;
        }
        
        .image-preview {
            margin-top: 15px;
        }
        
        .image-preview img {
            max-width: 100%;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📤 Upload File ke R2</h1>
        <p class="subtitle">Cloudflare R2 Storage</p>
        
        <div id="alertBox" class="alert"></div>
        <div id="loaderBox" class="loader">
            <div class="spinner"></div>
            <p>Sedang upload...</p>
        </div>
        
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
            
            <div class="form-group">
                <label for="fileInput">Pilih File</label>
                <label for="fileInput" class="file-upload-label" id="fileLabel">
                    📁 Klik atau drag file di sini
                </label>
                <input type="file" id="fileInput" name="file" required>
                <div class="file-name" id="fileName"></div>
            </div>
            
            <button type="submit" id="submitBtn">Upload File</button>
        </form>
        
        <div id="responseBox" class="response-box">
            <h3>✅ Upload Berhasil!</h3>
            <p style="margin-bottom: 10px;">
                <strong>File Path:</strong><br>
                <span id="filePath" class="response-text"></span>
            </p>
            <p style="margin-bottom: 10px;">
                <strong>URL R2:</strong><br>
                <span id="fileUrl" class="response-text"></span>
            </p>
            <div class="image-preview" id="imagePreview"></div>
        </div>
    </div>
    
    <script>
        const fileInput = document.getElementById('fileInput');
        const fileLabel = document.getElementById('fileLabel');
        const fileName = document.getElementById('fileName');
        const uploadForm = document.getElementById('uploadForm');
        const alertBox = document.getElementById('alertBox');
        const loaderBox = document.getElementById('loaderBox');
        const responseBox = document.getElementById('responseBox');
        const submitBtn = document.getElementById('submitBtn');
        
        // Handle file selection
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                fileName.textContent = '✓ File selected: ' + e.target.files[0].name;
            }
        });
        
        // Drag and drop
        fileLabel.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileLabel.classList.add('dragover');
        });
        
        fileLabel.addEventListener('dragleave', () => {
            fileLabel.classList.remove('dragover');
        });
        
        fileLabel.addEventListener('drop', (e) => {
            e.preventDefault();
            fileLabel.classList.remove('dragover');
            
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                fileName.textContent = '✓ File selected: ' + e.dataTransfer.files[0].name;
            }
        });
        
        // Handle form submission
        uploadForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!fileInput.files.length) {
                showAlert('error', 'Pilih file dulu!');
                return;
            }
            
            // Show loader
            loaderBox.style.display = 'block';
            responseBox.style.display = 'none';
            submitBtn.disabled = true;
            
            const formData = new FormData(uploadForm);
            
            try {
                const response = await fetch('/api/upload', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-Token': document.querySelector('input[name="csrf_token"]').value
                    }
                });
                
                const data = await response.json();
                
                loaderBox.style.display = 'none';
                
                if (data.success) {
                    showAlert('success', 'File berhasil di-upload ke R2! 🎉');
                    
                    // Show response
                    document.getElementById('filePath').textContent = data.path;
                    document.getElementById('fileUrl').textContent = data.url;
                    
                    // Preview image jika file adalah gambar
                    if (fileInput.files[0].type.startsWith('image/')) {
                        const imagePreview = document.getElementById('imagePreview');
                        imagePreview.innerHTML = '<img src="' + data.url + '" alt="Preview">';
                    }
                    
                    responseBox.style.display = 'block';
                    
                    // Reset form
                    setTimeout(() => {
                        uploadForm.reset();
                        fileName.textContent = '';
                        submitBtn.disabled = false;
                    }, 2000);
                } else {
                    showAlert('error', 'Error: ' + data.message);
                    submitBtn.disabled = false;
                }
                
            } catch (error) {
                loaderBox.style.display = 'none';
                showAlert('error', 'Connection error: ' + error.message);
                submitBtn.disabled = false;
            }
        });
        
        function showAlert(type, message) {
            alertBox.className = 'alert alert-' + type;
            alertBox.textContent = message;
            alertBox.style.display = 'block';
            
            if (type === 'success') {
                setTimeout(() => {
                    alertBox.style.display = 'none';
                }, 5000);
            }
        }
    </script>
</body>
</html>
