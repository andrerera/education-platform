@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-12 bg-white p-8 rounded-xl shadow-lg">
    <div class="mb-8">
        <h1 class="text-3xl font-semibold text-gray-800">🎓 Create New Course</h1>
        <p class="text-gray-600 text-sm mt-2">Fill in the details below to add a new course for review.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <form id="courseForm" action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3"
                placeholder="e.g. Mastering Laravel for Beginners"
                value="{{ old('title') }}">
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Thumbnail -->
        <div>
            <label for="thumbnail" class="block text-sm font-medium text-gray-700">Thumbnail (Optional)</label>
            <input type="file" name="thumbnail" id="thumbnail" accept="image/*"
                class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('thumbnail')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Content Type -->
        <div>
            <label for="content_type" class="block text-sm font-medium text-gray-700">Content Type <span class="text-red-500">*</span></label>
            <select name="content_type" id="content_type" required
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3">
                <option value="">-- Choose Type --</option>
                <option value="article" {{ old('content_type') == 'article' ? 'selected' : '' }}>Article</option>
                <option value="video" {{ old('content_type') == 'video' ? 'selected' : '' }}>Video</option>
                <option value="audio" {{ old('content_type') == 'audio' ? 'selected' : '' }}>Audio Podcast</option>
                <option value="pdf" {{ old('content_type') == 'pdf' ? 'selected' : '' }}>PDF</option>
            </select>
            @error('content_type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div id="desc_wrapper" class="hidden">
            <label for="description" class="block text-sm font-medium text-gray-700">Article Description</label>
            <textarea name="description" id="description" rows="5"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3"
                placeholder="Write your article content here...">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
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
                @error('video_file')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div id="video_url" class="hidden">
                <label for="video_url_input" class="block text-sm font-medium text-gray-700">Video URL</label>
                <input type="url" name="video_url" id="video_url_input" placeholder="https://youtube.com/..."
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-3">
                @error('video_url')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Audio -->
        <div id="audio_wrapper" class="hidden">
            <label for="audio_file" class="block text-sm font-medium text-gray-700">Upload Audio (.mp3)</label>
            <input type="file" name="audio_file" id="audio_file" accept="audio/mpeg"
                class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('audio_file')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- PDF -->
        <div id="pdf_wrapper" class="hidden">
            <label for="pdf_file" class="block text-sm font-medium text-gray-700">Upload PDF</label>
            <input type="file" name="pdf_file" id="pdf_file" accept="application/pdf"
                class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('pdf_file')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center pt-6">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-indigo-600 font-medium">Cancel</a>
            <button type="submit" id="submitBtn"
                class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                Submit
            </button>
        </div>
    </form>
</div>

<!-- JavaScript: Dynamic Content Display -->
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
    const videoFileInput = document.getElementById('video_file');
    const videoUrlInput = document.getElementById('video_url_input');

    // Show relevant content section based on content_type
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

    // Toggle video upload/URL inputs
    videoRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'upload') {
                videoUpload.classList.remove('hidden');
                videoURL.classList.add('hidden');
                videoFileInput.name = 'video_file';
                videoUrlInput.name = '';
                videoUrlInput.value = ''; // Clear URL input
            } else {
                videoUpload.classList.add('hidden');
                videoURL.classList.remove('hidden');
                videoFileInput.name = '';
                videoFileInput.value = ''; // Clear file input
                videoUrlInput.name = 'video_url';
            }
        });
    });
});
</script>
@endsection