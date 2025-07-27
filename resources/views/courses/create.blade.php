@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-12 bg-white p-8 rounded-xl shadow-lg">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">🎓 Create New Course</h1>
        <p class="text-gray-500 mt-2">Fill in the details below to add a new course for review.</p>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg p-8 max-w-sm mx-4 text-center">
            <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-indigo-600 mx-auto mb-4"></div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Uploading Content...</h3>
            <p class="text-gray-600 text-sm">Please wait while we process your files</p>
            <div class="mt-4">
                <div class="bg-gray-200 rounded-full h-2">
                    <div id="progressBar" class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <p id="progressText" class="text-sm text-gray-600 mt-2">0%</p>
            </div>
        </div>
    </div>

    <form id="courseForm" action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3"
                placeholder="e.g. Mastering Laravel for Beginners"
                value="{{ old('title') }}">
        </div>

        <!-- Thumbnail -->
        <div>
            <label for="thumbnail" class="block text-sm font-medium text-gray-700">Thumbnail (Optional)</label>
            <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <div id="thumbnailPreview" class="mt-2 hidden">
                <img id="thumbnailImg" src="" alt="Thumbnail preview" class="w-24 h-24 object-cover rounded-lg">
            </div>
        </div>

        <!-- Content Type -->
        <div>
            <label for="content_type" class="block text-sm font-medium text-gray-700">Content Type <span class="text-red-500">*</span></label>
            <select name="content_type" id="content_type" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3">
                <option value="">-- Choose Type --</option>
                <option value="article">Article</option>
                <option value="video">Video</option>
                <option value="audio">Audio Podcast</option>
                <option value="pdf">PDF</option>
            </select>
        </div>

        <!-- Description -->
        <div id="desc_wrapper" class="hidden">
            <label for="description" class="block text-sm font-medium text-gray-700">Article Description</label>
            <textarea name="description" id="description" rows="5"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3"
                placeholder="Write your article content here...">{{ old('description') }}</textarea>
        </div>

        <!-- Video -->
        <div id="video_wrapper" class="hidden space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Video Source</label>
                <div class="mt-2 flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="video_option" value="upload" checked class="form-radio text-indigo-600">
                        <span class="ml-2 text-gray-700">Upload Video</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="video_option" value="url" class="form-radio text-indigo-600">
                        <span class="ml-2 text-gray-700">Paste Video URL</span>
                    </label>
                </div>
            </div>

            <div id="video_upload">
                <label for="video_file" class="block text-sm font-medium text-gray-700">Upload Video File</label>
                <input type="file" name="video_file" id="video_file" accept="video/*"
                    class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <div id="videoInfo" class="mt-2 text-sm text-gray-600 hidden">
                    <span id="videoName"></span> - <span id="videoSize"></span>
                </div>
            </div>

            <div id="video_url" class="hidden">
                <label for="video_url_input" class="block text-sm font-medium text-gray-700">Video URL</label>
                <input type="url" name="video_url" id="video_url_input" placeholder="https://youtube.com/watch?v=... or https://vimeo.com/..."
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3">
                <div class="mt-2 text-sm text-gray-500">
                    <strong>Supported platforms:</strong> YouTube, Vimeo, or direct video links
                </div>
                <div id="urlPreview" class="mt-3 hidden">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-green-800 text-sm font-medium">Valid URL detected</span>
                        </div>
                        <p class="text-green-700 text-sm mt-1" id="detectedPlatform"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audio -->
        <div id="audio_wrapper" class="hidden">
            <label for="audio_file" class="block text-sm font-medium text-gray-700">Upload Audio (.mp3)</label>
            <input type="file" name="audio_file" id="audio_file" accept="audio/mpeg"
                class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <div id="audioInfo" class="mt-2 text-sm text-gray-600 hidden">
                <span id="audioName"></span> - <span id="audioSize"></span>
            </div>
        </div>

        <!-- PDF -->
        <div id="pdf_wrapper" class="hidden">
            <label for="pdf_file" class="block text-sm font-medium text-gray-700">Upload PDF</label>
            <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf"
                class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <div id="pdfInfo" class="mt-2 text-sm text-gray-600 hidden">
                <span id="pdfName"></span> - <span id="pdfSize"></span>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center pt-6">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-indigo-600 font-medium">Cancel</a>
            <button type="submit" id="submitBtn"
                class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="submitText">Submit</span>
                <span id="submitLoading" class="hidden">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Uploading...
                </span>
            </button>
        </div>
    </form>
</div>

<!-- JavaScript: Dynamic Content Display & Upload Progress -->
<!-- JavaScript: Dynamic Content Display & Upload Progress -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const contentType = document.getElementById('content_type');
    const descWrapper = document.getElementById('desc_wrapper');
    const videoWrapper = document.getElementById('video_wrapper');
    const audioWrapper = document.getElementById('audio_wrapper');
    const pdfWrapper = document.getElementById('pdf_wrapper');
    const videoUpload = document.getElementById('video_upload');
    const videoURL = document.getElementById('video_url');
    const videoRadios = document.querySelectorAll('input[name="video_option"]');
    const form = document.getElementById('courseForm');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitLoading = document.getElementById('submitLoading');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const thumbnailInput = document.getElementById('thumbnail');
    const videoFileInput = document.getElementById('video_file');
    const audioFileInput = document.getElementById('audio_file');
    const pdfFileInput = document.getElementById('pdf_file');
    const videoUrlInput = document.getElementById('video_url_input');
    const urlPreview = document.getElementById('urlPreview');
    const detectedPlatform = document.getElementById('detectedPlatform');

    // Content type change handler
    contentType.addEventListener('change', function () {
        descWrapper.classList.add('hidden');
        videoWrapper.classList.add('hidden');
        audioWrapper.classList.add('hidden');
        pdfWrapper.classList.add('hidden');
        switch (this.value) {
            case 'article':
                descWrapper.classList.remove('hidden');
                break;
            case 'video':
                videoWrapper.classList.remove('hidden');
                break;
            case 'audio':
                audioWrapper.classList.remove('hidden');
                break;
            case 'pdf':
                pdfWrapper.classList.remove('hidden');
                break;
        }
    });

    // Video radio button handler
    videoRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'upload') {
                videoUpload.classList.remove('hidden');
                videoURL.classList.add('hidden');
                videoFileInput.disabled = false;
                videoFileInput.name = 'video_file';
                videoUrlInput.value = '';
                videoUrlInput.disabled = true;
                videoUrlInput.name = '';
                document.getElementById('videoInfo').classList.add('hidden');
            } else {
                videoUpload.classList.add('hidden');
                videoURL.classList.remove('hidden');
                videoUrlInput.disabled = false;
                videoUrlInput.name = 'video_url';
                videoFileInput.value = '';
                videoFileInput.disabled = true;
                videoFileInput.name = '';
                document.getElementById('videoInfo').classList.add('hidden');
            }
        });
    });

    // File info display functions
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showFileInfo(input, nameElement, sizeElement, infoElement) {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                nameElement.textContent = file.name;
                sizeElement.textContent = formatFileSize(file.size);
                infoElement.classList.remove('hidden');
            } else {
                infoElement.classList.add('hidden');
            }
        });
    }

    // Thumbnail preview
    thumbnailInput.addEventListener('change', function() {
        const file = this.files[0];
        const preview = document.getElementById('thumbnailPreview');
        const img = document.getElementById('thumbnailImg');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    });

    // Setup file info displays
    showFileInfo(videoFileInput, document.getElementById('videoName'), document.getElementById('videoSize'), document.getElementById('videoInfo'));
    showFileInfo(audioFileInput, document.getElementById('audioName'), document.getElementById('audioSize'), document.getElementById('audioInfo'));
    showFileInfo(pdfFileInput, document.getElementById('pdfName'), document.getElementById('pdfSize'), document.getElementById('pdfInfo'));

    // Video URL validation and preview
    videoUrlInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url) {
            validateVideoUrl(url);
        } else {
            urlPreview.classList.add('hidden');
        }
    });

    function validateVideoUrl(url) {
        let platform = '';
        let isValid = false;
        if (url.includes('youtube.com/watch') || url.includes('youtu.be/')) {
            platform = 'YouTube';
            isValid = true;
        } else if (url.includes('vimeo.com/')) {
            platform = 'Vimeo';
            isValid = true;
        } else if (url.match(/\.(mp4|webm|ogg|avi|mov|wmv|flv|mkv)(\?.*)?$/i)) {
            platform = 'Direct Video Link';
            isValid = true;
        } else if (url.includes('dailymotion.com') || url.includes('twitch.tv')) {
            platform = url.includes('dailymotion.com') ? 'Dailymotion' : 'Twitch';
            isValid = true;
        }
        if (isValid) {
            detectedPlatform.textContent = `Platform: ${platform}`;
            urlPreview.classList.remove('hidden');
        } else {
            urlPreview.classList.add('hidden');
        }
    }

    // Form submission with AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const selectedContentType = contentType.value;
        const videoOption = document.querySelector('input[name="video_option"]:checked')?.value;
        let hasFiles = false;
        let isVideoUrl = false;

        // Check for file uploads
        if (thumbnailInput.files.length > 0) hasFiles = true;
        if (selectedContentType === 'video') {
            if (videoOption === 'upload' && videoFileInput.files.length > 0) {
                hasFiles = true;
            } else if (videoOption === 'url' && videoUrlInput.value.trim()) {
                isVideoUrl = true;
            }
        } else if (selectedContentType === 'audio' && audioFileInput.files.length > 0) {
            hasFiles = true;
        } else if (selectedContentType === 'pdf' && pdfFileInput.files.length > 0) {
            hasFiles = true;
        }

        // Re-enable file inputs to ensure they're included
        if (videoFileInput.disabled) videoFileInput.disabled = false;
        if (videoUrlInput.disabled) videoUrlInput.disabled = false;

        // Prepare form data
        const formData = new FormData(form);
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');

        if (hasFiles) {
            showLoadingOverlay();
            // Real progress tracking
            const xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', form.querySelector('input[name="_token"]').value);

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percent = (e.loaded / e.total) * 100;
                    progressBar.style.width = percent + '%';
                    progressText.textContent = Math.round(percent) + '%';
                }
            };

            xhr.onload = function() {
                hideLoadingOverlay();
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
                if (xhr.status >= 200 && xhr.status < 300) {
                    const response = JSON.parse(xhr.responseText);
                    window.location.href = response.redirect || '/';
                } else {
                    alert('Upload failed: ' + xhr.responseText);
                }
            };

            xhr.onerror = function() {
                hideLoadingOverlay();
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
                alert('Upload failed due to a network error.');
            };

            xhr.send(formData);
        } else {
            if (isVideoUrl) {
                showQuickProcessing();
            }
            // Non-file submissions
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                }
            }).then(response => response.json())
              .then(data => {
                  submitBtn.disabled = false;
                  submitText.classList.remove('hidden');
                  submitLoading.classList.add('hidden');
                  window.location.href = data.redirect || '/';
              })
              .catch(error => {
                  submitBtn.disabled = false;
                  submitText.classList.remove('hidden');
                  submitLoading.classList.add('hidden');
                  alert('Submission failed: ' + error.message);
              });
        }
    });

    function showQuickProcessing() {
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing URL...
        `;
    }

    function showLoadingOverlay() {
        loadingOverlay.classList.remove('hidden');
        loadingOverlay.classList.add('flex');
        progressBar.style.width = '0%';
        progressText.textContent = '0%';
    }

    function hideLoadingOverlay() {
        loadingOverlay.classList.add('hidden');
        loadingOverlay.classList.remove('flex');
    }
});
</script>

<style>
/* Additional styles for better loading experience */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Custom file input styling */
input[type="file"]::-webkit-file-upload-button {
    transition: all 0.3s ease;
}

input[type="file"]::-webkit-file-upload-button:hover {
    transform: translateY(-1px);
}
</style>
@endsection