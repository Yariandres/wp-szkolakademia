document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('course-structure-editor');
    if (!editor) return;

    // Add Section
    editor.querySelector('.add-section').addEventListener('click', function() {
        const sections = editor.querySelector('.course-sections');
        const sectionIndex = document.querySelectorAll('.section-item').length;
        
        const sectionHtml = `
            <div class="section-item">
                <div class="section-header flex justify-between items-center mb-2">
                    <h3 class="font-bold">Section</h3>
                    <button type="button" class="remove-section button-link-delete">×</button>
                </div>
                <input type="text" 
                       name="course_structure[${sectionIndex}][title]" 
                       class="widefat"
                       placeholder="Section Title">
                
                <div class="chapters mt-4 ml-4">
                    <button type="button" class="add-chapter button-secondary">
                        Add Chapter
                    </button>
                </div>
            </div>
        `;
        
        this.insertAdjacentHTML('beforebegin', sectionHtml);
    });

    // Add Chapter
    editor.addEventListener('click', function(e) {
        if (e.target.matches('.add-chapter')) {
            const section = e.target.closest('.section-item');
            const sectionIndex = Array.from(document.querySelectorAll('.section-item')).indexOf(section);
            const chapters = section.querySelectorAll('.chapter-item');
            const chapterIndex = chapters.length;
            
            const chapterHtml = `
                <div class="chapter-item">
                    <div class="chapter-header flex justify-between items-center mb-2">
                        <h4 class="font-medium">Chapter</h4>
                        <button type="button" class="remove-chapter button-link-delete">×</button>
                    </div>
                    <input type="text" 
                           name="course_structure[${sectionIndex}][chapters][${chapterIndex}][title]" 
                           class="widefat"
                           placeholder="Chapter Title">
                    
                    <div class="lessons mt-2 ml-4">
                        <button type="button" class="add-lesson button-secondary">
                            Add Lesson
                        </button>
                    </div>
                </div>
            `;
            
            e.target.insertAdjacentHTML('beforebegin', chapterHtml);
        }
    });

    // Add Lesson
    editor.addEventListener('click', function(e) {
        if (e.target.matches('.add-lesson')) {
            const section = e.target.closest('.section-item');
            const chapter = e.target.closest('.chapter-item');
            const sectionIndex = Array.from(document.querySelectorAll('.section-item')).indexOf(section);
            const chapterIndex = Array.from(section.querySelectorAll('.chapter-item')).indexOf(chapter);
            const lessons = chapter.querySelectorAll('.lesson-item');
            const lessonIndex = lessons.length;
            
            const lessonHtml = `
                <div class="lesson-item">
                    <div class="lesson-header flex justify-between items-center mb-2">
                        <span class="text-sm font-medium">Lesson</span>
                        <button type="button" class="remove-lesson button-link-delete">×</button>
                    </div>
                    <input type="text" 
                           name="course_structure[${sectionIndex}][chapters][${chapterIndex}][lessons][${lessonIndex}][title]" 
                           class="widefat mb-2"
                           placeholder="Lesson Title">
                    <input type="url" 
                           name="course_structure[${sectionIndex}][chapters][${chapterIndex}][lessons][${lessonIndex}][video_url]" 
                           class="widefat"
                           placeholder="Video URL">
                </div>
            `;
            
            e.target.insertAdjacentHTML('beforebegin', lessonHtml);
        }
    });

    // Remove items
    editor.addEventListener('click', function(e) {
        if (e.target.matches('.remove-section')) {
            e.target.closest('.section-item').remove();
        } else if (e.target.matches('.remove-chapter')) {
            e.target.closest('.chapter-item').remove();
        } else if (e.target.matches('.remove-lesson')) {
            e.target.closest('.lesson-item').remove();
        }
    });
}); 