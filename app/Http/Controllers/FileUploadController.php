<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class FileUploadController extends Controller
{
    /**
     * Upload file ke R2 menggunakan AWS SDK langsung
     */
    public function upload(Request $request)
    {
        // Validate
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        try {
            // Get file
            $file = $request->file('file');
            
            // Cek apakah file ada
            if (!$file || !$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak valid atau tidak terdeteksi',
                ], 400);
            }
            
            // Init S3 Client
            $s3Client = new S3Client([
                'version' => 'latest',
                'region'  => env('R2_REGION', 'auto'),
                'endpoint' => env('R2_ENDPOINT'),
                'credentials' => [
                    'key'    => env('R2_KEY'),
                    'secret' => env('R2_SECRET'),
                ]
            ]);
            
            // Buat nama file dengan timestamp untuk menghindari duplikat
            $originalName = $file->getClientOriginalName();
            $filename = time() . '_' . $originalName;
            $bucket = env('R2_BUCKET');
            $key = 'uploads/' . $filename;
            
            // Upload ke R2
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key'    => $key,
                'Body'   => fopen($file->getRealPath(), 'r'),
                'ACL'    => 'public-read', // Untuk public access
            ]);
            
            // Get URL
            $url = $s3Client->getObjectUrl($bucket, $key);

            return response()->json([
                'success' => true,
                'message' => 'File berhasil di-upload ke R2',
                'path' => $key,
                'url' => $url,
                'filename' => $filename,
            ], 200);

        } catch (AwsException $e) {
            \Log::error('AWS Upload Error', [
                'message' => $e->getMessage(),
                'code' => $e->getAwsErrorCode(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'AWS Error: ' . $e->getAwsErrorCode() . ' - ' . $e->getMessage(),
            ], 500);
            
        } catch (\Exception $e) {
            \Log::error('Upload Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List file dari R2
     */
    public function list()
    {
        try {
            $s3Client = new S3Client([
                'version' => 'latest',
                'region'  => env('R2_REGION', 'auto'),
                'endpoint' => env('R2_ENDPOINT'),
                'credentials' => [
                    'key'    => env('R2_KEY'),
                    'secret' => env('R2_SECRET'),
                ]
            ]);
            
            $result = $s3Client->listObjects([
                'Bucket' => env('R2_BUCKET'),
                'Prefix' => 'uploads/'
            ]);

            $files = [];
            if (isset($result['Contents'])) {
                foreach ($result['Contents'] as $item) {
                    $files[] = [
                        'key' => $item['Key'],
                        'size' => $item['Size'],
                        'modified' => $item['LastModified'],
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'files' => $files,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete file dari R2
     */
    public function delete(Request $request)
    {
        try {
            $key = $request->input('key');
            
            $s3Client = new S3Client([
                'version' => 'latest',
                'region'  => env('R2_REGION', 'auto'),
                'endpoint' => env('R2_ENDPOINT'),
                'credentials' => [
                    'key'    => env('R2_KEY'),
                    'secret' => env('R2_SECRET'),
                ]
            ]);
            
            $s3Client->deleteObject([
                'Bucket' => env('R2_BUCKET'),
                'Key'    => $key
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File berhasil dihapus dari R2',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
