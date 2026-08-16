@extends('layouts.app')

@section('title', 'Add Movie - Admin')

@section('content')
<div class="admin-page">
    <div class="container">
        <div class="admin-header">
            <h1>Add Movie</h1>
            <p>Create a new movie for the homepage and movie pages.</p>
        </div>

        <form action="{{ route('admin.movies.store') }}" method="POST" class="admin-form" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="release_year">Release Year</label>
                    <input type="number" id="release_year" name="release_year" value="{{ old('release_year') }}">
                </div>

                <div class="form-group">
                    <label for="release_date">Release Date</label>
                    <input type="date" id="release_date" name="release_date" value="{{ old('release_date') }}">
                </div>

                <div class="form-group">
                    <label for="duration">Duration (minutes)</label>
                    <input type="number" id="duration" name="duration" value="{{ old('duration') }}">
                </div>

                <div class="form-group">
                    <label for="rating">Rating (0-10)</label>
                    <input type="number" step="0.1" id="rating" name="rating" value="{{ old('rating', 0) }}">
                </div>

                <div class="form-group">
                    <label for="view_count">View Count</label>
                    <input type="number" id="view_count" name="view_count" value="{{ old('view_count', 0) }}">
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        @foreach(['draft', 'published', 'archived', 'coming_soon'] as $status)
                        <option value="{{ $status }}" {{ old('status', 'draft') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="content_rating">Content Rating</label>
                    <select id="content_rating" name="content_rating">
                        <option value="">None</option>
                        @foreach(['G', 'PG', 'PG-13', 'R', 'NC-17'] as $rating)
                        <option value="{{ $rating }}" {{ old('content_rating') === $rating ? 'selected' : '' }}>{{ $rating }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="thumbnail">Thumbnail</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                    <p class="input-hint">Upload movie thumbnail image</p>
                </div>

                <div class="form-group full-width">
                    <label for="banner">Banner</label>
                    <input type="file" id="banner" name="banner" accept="image/*">
                    <p class="input-hint">Upload movie banner image (optional)</p>
                </div>

                <div class="form-group full-width">
                    <label for="director">Director</label>
                    <input type="text" id="director" name="director" value="{{ old('director') }}">
                </div>

                <div class="form-group full-width">
                    <label for="cast">Cast</label>
                    <input type="text" id="cast" name="cast" value="{{ old('cast') }}">
                </div>

                <div class="form-group full-width">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="5">{{ old('description') }}</textarea>
                </div>

                <div class="form-group full-width">
                    <label>Genres</label>
                    <div class="genre-checkboxes">
                        @foreach($genres as $genre)
                        <label class="checkbox-label">
                            <input type="checkbox" name="genres[]" value="{{ $genre->id }}"
                                {{ in_array($genre->id, old('genres', [])) ? 'checked' : '' }}>
                            {{ $genre->name }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                        Featured on homepage hero
                    </label>
                </div>
            </div>

            <!-- Video Upload Section -->
            <div class="video-upload-section">
                <h3>Video Upload</h3>
                <p class="section-description">Upload video files or provide URLs for streaming. Main video is required for playback.</p>
                
                <div class="video-upload-tabs">
                    <button type="button" class="tab-btn active" data-tab="file-upload">Upload Files</button>
                    <button type="button" class="tab-btn" data-tab="url-input">Video URLs</button>
                </div>

                <!-- File Upload Tab -->
                <div class="tab-content active" id="file-upload">
                    <div class="video-upload-grid">
                        <!-- Main Movie Video -->
                        <div class="video-upload-card primary-video">
                            <div class="video-card-header">
                                <h4>Main Movie <span class="required">*</span></h4>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="main_video_is_primary" value="1" checked>
                                    Set as primary
                                </label>
                            </div>
                            <div class="video-upload-area" id="main-video-dropzone">
                                <input type="file" name="main_video" id="main_video" accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-msvideo,video/x-matroska" class="video-file-input">
                                <div class="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Drag & drop video file or click to browse</p>
                                    <p class="upload-hint">MP4, WebM, OGG, MOV, AVI, MKV (Max 2GB)</p>
                                </div>
                                <div class="file-preview" id="main-video-preview" style="display: none;">
                                    <div class="file-info">
                                        <i class="fas fa-file-video"></i>
                                        <span class="file-name"></span>
                                        <span class="file-size"></span>
                                    </div>
                                    <button type="button" class="remove-file" onclick="removeFile('main_video')">×</button>
                                </div>
                            </div>
                            <div class="video-details">
                                <div class="form-group">
                                    <label>Video Title</label>
                                    <input type="text" name="main_video_title" value="{{ old('main_video_title', 'Main Movie') }}" placeholder="Main Movie">
                                </div>
                                <div class="form-group">
                                    <label>Quality</label>
                                    <select name="main_video_quality">
                                        <option value="">Auto-detect</option>
                                        <option value="480p">480p</option>
                                        <option value="720p">720p</option>
                                        <option value="1080p">1080p</option>
                                        <option value="4K">4K</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Trailer Video -->
                        <div class="video-upload-card">
                            <div class="video-card-header">
                                <h4>Trailer</h4>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="trailer_is_primary" value="1">
                                    Set as primary
                                </label>
                            </div>
                            <div class="video-upload-area" id="trailer-dropzone">
                                <input type="file" name="trailer_video" id="trailer_video" accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-msvideo,video/x-matroska" class="video-file-input">
                                <div class="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Drag & drop trailer file or click to browse</p>
                                    <p class="upload-hint">Optional</p>
                                </div>
                                <div class="file-preview" id="trailer-video-preview" style="display: none;">
                                    <div class="file-info">
                                        <i class="fas fa-file-video"></i>
                                        <span class="file-name"></span>
                                        <span class="file-size"></span>
                                    </div>
                                    <button type="button" class="remove-file" onclick="removeFile('trailer_video')">×</button>
                                </div>
                            </div>
                            <div class="video-details">
                                <div class="form-group">
                                    <label>Video Title</label>
                                    <input type="text" name="trailer_video_title" value="{{ old('trailer_video_title', 'Official Trailer') }}" placeholder="Official Trailer">
                                </div>
                                <div class="form-group">
                                    <label>Quality</label>
                                    <select name="trailer_video_quality">
                                        <option value="">Auto-detect</option>
                                        <option value="480p">480p</option>
                                        <option value="720p">720p</option>
                                        <option value="1080p">1080p</option>
                                        <option value="4K">4K</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- URL Input Tab -->
                <div class="tab-content" id="url-input">
                    <div class="video-url-grid">
                        <div class="video-url-card primary-video">
                            <div class="video-card-header">
                                <h4>Main Movie URL <span class="required">*</span></h4>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="main_video_url_is_primary" value="1" checked>
                                    Set as primary
                                </label>
                            </div>
                            <div class="form-group">
                                <label>Video URL</label>
                                <input type="url" name="main_video_url" value="{{ old('main_video_url') }}" placeholder="https://example.com/video.mp4">
                                <p class="input-hint">Enter direct video file URL or streaming URL</p>
                            </div>
                            <div class="video-details">
                                <div class="form-group">
                                    <label>Video Title</label>
                                    <input type="text" name="main_video_url_title" value="{{ old('main_video_url_title', 'Main Movie') }}" placeholder="Main Movie">
                                </div>
                                <div class="form-group">
                                    <label>Quality</label>
                                    <select name="main_video_url_quality">
                                        <option value="">Unknown</option>
                                        <option value="480p">480p</option>
                                        <option value="720p">720p</option>
                                        <option value="1080p">1080p</option>
                                        <option value="4K">4K</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="video-url-card">
                            <div class="video-card-header">
                                <h4>Trailer URL</h4>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="trailer_video_url_is_primary" value="1">
                                    Set as primary
                                </label>
                            </div>
                            <div class="form-group">
                                <label>Video URL</label>
                                <input type="url" name="trailer_video_url" value="{{ old('trailer_video_url') }}" placeholder="https://example.com/trailer.mp4">
                                <p class="input-hint">Optional trailer URL</p>
                            </div>
                            <div class="video-details">
                                <div class="form-group">
                                    <label>Video Title</label>
                                    <input type="text" name="trailer_video_url_title" value="{{ old('trailer_video_url_title', 'Official Trailer') }}" placeholder="Official Trailer">
                                </div>
                                <div class="form-group">
                                    <label>Quality</label>
                                    <select name="trailer_video_url_quality">
                                        <option value="">Unknown</option>
                                        <option value="480p">480p</option>
                                        <option value="720p">720p</option>
                                        <option value="1080p">1080p</option>
                                        <option value="4K">4K</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Create Movie</button>
                <a href="{{ route('admin.movies.index') }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
.admin-page { padding: 2rem 0 4rem; background: #0a0a0a; min-height: 80vh; color: #fff; }
.admin-header { margin-bottom: 2rem; }
.admin-form { background: #141414; padding: 2rem; border-radius: 8px; }
.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
.form-group { display: flex; flex-direction: column; gap: 0.5rem; }
.form-group.full-width { grid-column: 1 / -1; }
.form-group label { font-weight: 600; }
.form-group input, .form-group select, .form-group textarea {
    padding: 0.75rem; border-radius: 4px; border: 1px solid #2a2a2a;
    background: #1f1f1f; color: #fff;
}
.genre-checkboxes { display: flex; flex-wrap: wrap; gap: 1rem; }
.checkbox-label { display: flex; align-items: center; gap: 0.5rem; }
.form-actions { margin-top: 2rem; display: flex; gap: 1rem; }
.btn { padding: 0.75rem 1.5rem; background: #e50914; color: #fff; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; }
.btn-outline { background: transparent; border: 1px solid #fff; }
.error { color: #ff4444; font-size: 0.9rem; }

/* Video Upload Section */
.video-upload-section {
    margin-top: 2rem;
    padding: 2rem;
    background: #1a1a1a;
    border-radius: 8px;
    border: 1px solid #2a2a2a;
}

.video-upload-section h3 {
    margin-bottom: 0.5rem;
    color: #fff;
}

.section-description {
    color: #a3a3a3;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.video-upload-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid #2a2a2a;
    padding-bottom: 0.5rem;
}

.tab-btn {
    padding: 0.75rem 1.5rem;
    background: transparent;
    border: none;
    color: #a3a3a3;
    cursor: pointer;
    font-weight: 600;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.tab-btn:hover {
    color: #fff;
    background: rgba(255,255,255,0.05);
}

.tab-btn.active {
    color: #e50914;
    background: rgba(229, 9, 20, 0.1);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.video-upload-grid,
.video-url-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.video-upload-card,
.video-url-card {
    background: #1f1f1f;
    border: 1px solid #2a2a2a;
    border-radius: 8px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.video-upload-card:hover,
.video-url-card:hover {
    border-color: #3a3a3a;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

.video-upload-card.primary-video,
.video-url-card.primary-video {
    border-color: #e50914;
    background: rgba(229, 9, 20, 0.05);
}

.video-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #2a2a2a;
}

.video-card-header h4 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
}

.required {
    color: #e50914;
    margin-left: 0.25rem;
}

.video-upload-area {
    position: relative;
    border: 2px dashed #3a3a3a;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    min-height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-upload-area:hover {
    border-color: #e50914;
    background: rgba(229, 9, 20, 0.05);
}

.video-upload-area.dragover {
    border-color: #e50914;
    background: rgba(229, 9, 20, 0.1);
}

.video-file-input {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
}

.upload-placeholder {
    color: #a3a3a3;
}

.upload-placeholder i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    color: #e50914;
}

.upload-placeholder p {
    margin: 0.25rem 0;
}

.upload-hint {
    font-size: 0.8rem;
    color: #737373;
}

.file-preview {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: #2a2a2a;
    border-radius: 4px;
}

.file-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.file-info i {
    color: #e50914;
    font-size: 1.25rem;
}

.file-name {
    color: #fff;
    font-weight: 500;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.file-size {
    color: #a3a3a3;
    font-size: 0.85rem;
}

.remove-file {
    background: #e50914;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-details {
    margin-top: 1rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

.video-details .form-group {
    margin: 0;
}

.input-hint {
    font-size: 0.8rem;
    color: #737373;
    margin-top: 0.25rem;
}

.current-file {
    font-size: 0.85rem;
    color: #e50914;
    margin-top: 0.25rem;
    font-weight: 500;
}

@media (max-width: 768px) { 
    .form-grid { grid-template-columns: 1fr; }
    .video-upload-grid,
    .video-url-grid {
        grid-template-columns: 1fr;
    }
    .video-details {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Remove active class from all tabs and contents
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        // Add active class to clicked tab and corresponding content
        this.classList.add('active');
        const tabId = this.dataset.tab;
        document.getElementById(tabId).classList.add('active');
    });
});

// File upload handling
function setupFileUpload(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const dropzone = input.closest('.video-upload-area');
    
    if (!input || !preview || !dropzone) return;
    
    // Click to upload
    dropzone.addEventListener('click', (e) => {
        if (e.target !== input) {
            input.click();
        }
    });
    
    // File selection
    input.addEventListener('change', (e) => {
        handleFileSelect(e.target.files[0], preview, dropzone);
    });
    
    // Drag and drop
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });
    
    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('dragover');
    });
    
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        
        if (e.dataTransfer.files.length > 0) {
            input.files = e.dataTransfer.files;
            handleFileSelect(e.dataTransfer.files[0], preview, dropzone);
        }
    });
}

function handleFileSelect(file, preview, dropzone) {
    if (!file) return;
    
    // Validate file type
    const validTypes = ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime', 'video/x-msvideo', 'video/x-matroska'];
    if (!validTypes.includes(file.type)) {
        alert('Please select a valid video file (MP4, WebM, OGG, MOV, AVI, MKV)');
        return;
    }
    
    // Validate file size (2GB max)
    const maxSize = 2 * 1024 * 1024 * 1024; // 2GB
    if (file.size > maxSize) {
        alert('File size exceeds 2GB limit');
        return;
    }
    
    // Show preview
    const placeholder = dropzone.querySelector('.upload-placeholder');
    const fileName = preview.querySelector('.file-name');
    const fileSize = preview.querySelector('.file-size');
    
    if (placeholder) placeholder.style.display = 'none';
    preview.style.display = 'flex';
    
    fileName.textContent = file.name;
    fileSize.textContent = formatFileSize(file.size);
}

function removeFile(inputId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(inputId + '-preview');
    const dropzone = input.closest('.video-upload-area');
    
    if (input) input.value = '';
    if (preview) preview.style.display = 'none';
    
    const placeholder = dropzone.querySelector('.upload-placeholder');
    if (placeholder) placeholder.style.display = 'block';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Initialize file uploads
document.addEventListener('DOMContentLoaded', function() {
    setupFileUpload('main_video', 'main-video-preview');
    setupFileUpload('trailer_video', 'trailer-video-preview');
});
</script>
@endsection
