document.addEventListener('DOMContentLoaded', function() {
    const videoPlayer = document.getElementById('course-video-player');
    const courseId = new URLSearchParams(window.location.search).get('course_id');
    let progressUpdateTimeout;

    console.log('Course player initialized', {
        courseId: courseId,
        nonce: szkolaAcademyData.nonce
    });

    // Update progress every 5 seconds while watching
    function updateProgress(currentTime, duration) {
        if (progressUpdateTimeout) {
            clearTimeout(progressUpdateTimeout);
        }

        progressUpdateTimeout = setTimeout(() => {
            const progress = Math.floor((currentTime / duration) * 100);
            
            // Send progress update to server
            fetch('/wp-admin/admin-ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'update_course_progress',
                    course_id: courseId,
                    progress: progress,
                    nonce: szkolaAcademyData.nonce
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update progress bar
                    const progressBar = document.querySelector('.progress-bar');
                    if (progressBar) {
                        progressBar.style.width = `${progress}%`;
                    }
                }
            })
            .catch(error => console.error('Error updating progress:', error));
        }, 5000);
    }

    // Add video event listeners if using HTML5 video
    if (videoPlayer && videoPlayer.tagName === 'VIDEO') {
        videoPlayer.addEventListener('timeupdate', () => {
            updateProgress(videoPlayer.currentTime, videoPlayer.duration);
        });
    }
});
