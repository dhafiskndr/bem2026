<!DOCTYPE html>
<html>
<head>
    <title>Test R2 Connection</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .box { border: 1px solid #ccc; padding: 15px; margin: 10px 0; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        code { background: #f5f5f5; padding: 10px; display: block; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>🔧 Test R2 Configuration</h1>
    
    <div class="box">
        <h2>Credentials Check</h2>
        <p><strong>R2_KEY:</strong> <code>{{ env('R2_KEY') ? 'SET ✓' : 'NOT SET ✗' }}</code></p>
        <p><strong>R2_SECRET:</strong> <code>{{ env('R2_SECRET') ? 'SET ✓' : 'NOT SET ✗' }}</code></p>
        <p><strong>R2_BUCKET:</strong> <code>{{ env('R2_BUCKET') }}</code></p>
        <p><strong>R2_ENDPOINT:</strong> <code>{{ env('R2_ENDPOINT') }}</code></p>
    </div>
    
    <div class="box">
        <h2>Filesystem Config</h2>
        <p>Default Disk: <code>{{ config('filesystems.default') }}</code></p>
        <p>R2 Disk Driver: <code>{{ config('filesystems.disks.r2.driver') }}</code></p>
    </div>
    
    <div class="box">
        <h2>Test Upload</h2>
        <form id="testForm" enctype="multipart/form-data">
            <input type="file" name="file" id="fileInput" required>
            <button type="submit">Test Upload</button>
        </form>
        <div id="result"></div>
    </div>
    
    <script>
        document.getElementById('testForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            
            try {
                const response = await fetch('/api/upload', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                const resultDiv = document.getElementById('result');
                
                if (result.success) {
                    resultDiv.innerHTML = '<div class="box success"><h3>✓ Upload Success!</h3><p>Path: ' + result.path + '</p><p>URL: <a href="' + result.url + '" target="_blank">' + result.url + '</a></p></div>';
                } else {
                    resultDiv.innerHTML = '<div class="box error"><h3>✗ Upload Failed</h3><p>' + result.message + '</p><p>Type: ' + result.error_type + '</p></div>';
                }
            } catch (error) {
                document.getElementById('result').innerHTML = '<div class="box error"><h3>✗ Error</h3><p>' + error.message + '</p></div>';
            }
        });
    </script>
</body>
</html>
