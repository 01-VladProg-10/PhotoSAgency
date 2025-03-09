function previewImages() {
    const preview = document.getElementById('image-preview');
    const files = document.getElementById('photos').files;
    
    // Limit to a maximum of 10 images
    if (files.length > 10) {
        alert("<?php echo $translations['photos_max_limit']; ?>");
        return;
    }
    
    preview.innerHTML = '';
    
    for (let i = 0; i < files.length; i++) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            preview.appendChild(img);
        };
        reader.readAsDataURL(files[i]);
    }
}

function previewVideo() {
    const preview = document.getElementById('video-preview');
    const file = document.getElementById('video').files[0];
    
    if (file) {
        // Check if the file type is valid for video
        const allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
        if (!allowedTypes.includes(file.type)) {
            alert("<?php echo $translations['invalid_video_type']; ?>"); // Add this translation in your JSON files
            return;
        }

        const videoElement = document.createElement('video');
        videoElement.setAttribute('controls', 'controls');
        videoElement.setAttribute('width', '200');
        videoElement.src = URL.createObjectURL(file);
        
        preview.innerHTML = '';  // Clear previous previews
        preview.appendChild(videoElement);
    }
}
