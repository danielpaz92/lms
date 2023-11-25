(function ($) {
    $(document).ready(function () {

        let content = '';

        // open|close requirements
        $('.masterstudy-course-player-assignments__task-button').click(function() {
            $('.masterstudy-course-player-assignments__task-content').slideToggle();
            $(this).toggleClass('masterstudy-course-player-assignments__task-button_rotate');
        });

        // submit assignment
        $('.masterstudy-course-player-navigation__send-assignment a').on('click', function (e) {
            e.preventDefault();
            if ($(this).attr('disabled')) {
                return;
            }
            let formData = new FormData();
            formData.append('content', content);
            formData.append('action', 'stm_lms_accept_draft_assignment');
            formData.append('nonce', assignments_data.submit_nonce);
            formData.append('draft_id', assignments_data.draft_id);
            formData.append('course_id', assignments_data.course_id);
            $.ajax({
                url: assignments_data.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('.masterstudy-course-player-navigation__send-assignment a').addClass('masterstudy-button_loading');
                },
                success: function () {
                    location.reload();
                    $('.masterstudy-course-player-navigation__send-assignment a').removeClass('masterstudy-button_loading');
                }
            });
        });

        // initialization of files upload area
        const dropArea = document.getElementById('masterstudy-file-upload-field');

        if ( dropArea !== null ) {

            //highlight files upload area
            dropArea.addEventListener('dragenter', function(e) {
                e.preventDefault();
                dropArea.classList.add('masterstudy-file-upload__field_highlight');
            });

            dropArea.addEventListener('dragover', function(e) {
                e.preventDefault();
            });

            // remove highlight files upload area
            dropArea.addEventListener('dragleave', function(e) {
                let rect = dropArea.getBoundingClientRect();
                let x = e.clientX;
                let y = e.clientY;
                if (!(x >= rect.left && x <= rect.right && y >= rect.top && y <= rect.bottom)) {
                    dropArea.classList.remove('masterstudy-file-upload__field_highlight');
                }
            });

            // add files to upload area
            dropArea.addEventListener('drop', function(e) {
                e.preventDefault();
                dropArea.classList.remove('masterstudy-file-upload__field_highlight');
                let files = e.dataTransfer.files;
                handleFiles(files);
            });

            // add files by click on button in upload area
            document.querySelector('.masterstudy-file-upload__field-button').addEventListener('click', function() {
                document.querySelector('.masterstudy-file-upload__input').click();
            });

            // watch change of files in input
            document.querySelector('.masterstudy-file-upload__input').addEventListener('change', function(e) {
                let files = e.target.files;
                handleFiles(files);
                e.target.value = null;
            });

            // save files
            function handleFiles(files) {
                const filesArray = Array.from(files);
                const loadingBar = document.querySelector('.masterstudy-file-upload__field-progress-bar-filled');
                const totalFiles = filesArray.length;
                let uploadedFiles = 0;
                let current_percent = 0;
                let total_percent = 0;
                filesArray.forEach(file => {
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('action', 'stm_lms_upload_assignment_file');
                    formData.append('nonce', assignments_data.add_nonce);
                    formData.append('draft_id', assignments_data.draft_id);
                    $.ajax({
                        url: assignments_data.ajax_url,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        // file download percentage tracker
                        xhr: function() {
                            const xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener('progress', function(event) {
                                if (event.lengthComputable) {
                                    current_percent = ( (event.loaded / event.total) / totalFiles ) * 100;
                                    current_percent = ( current_percent === 100 ) ? 95 : current_percent;
                                    if ( totalFiles === 1 ) {
                                        loadingBar.style.width = current_percent + '%';
                                    }
                                }
                            }, false);
                            return xhr;
                        },
                        beforeSend: function() {
                            $('.masterstudy-file-upload__field').addClass('masterstudy-file-upload__field_loading');
                        },
                        success: function (data) {
                            uploadedFiles++;
                            if ( data.error === false ) {
                                file.id = data.id;
                                generateFileHtml(file);
                            }
                            if ( totalFiles === 1 && data.error !== false ) {
                                $('.masterstudy-file-upload__field-error').text(data.message);
                                $('.masterstudy-file-upload__field-error').addClass('masterstudy-file-upload__field-error_show');
                            }
                            if ( totalFiles === uploadedFiles ) {
                                loadingBar.style.width = '100%';
                                setTimeout(function() {
                                    $('.masterstudy-file-upload__field').removeClass('masterstudy-file-upload__field_loading');
                                    loadingBar.style.width = '0';
                                }, 500);
                                setTimeout(function() {
                                    $('.masterstudy-file-upload__field-error').removeClass('masterstudy-file-upload__field-error_show');
                                }, 1500);
                            } else {
                                total_percent = total_percent + current_percent;
                                loadingBar.style.width = total_percent + '%';
                            }
                        }
                    });
                });
            }

            const formats = {
                'img': ['image/png','image/jpeg','image/gif','image/svg+xml'],
                'excel': ['application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
                'word': ['application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
                'powerpoint': ['application/vnd.ms-powerpoint','application/vnd.openxmlformats-officedocument.presentationml.presentation'],
                'pdf': ['application/pdf'],
                'video': ['video/mp4','video/avi','video/flv','video/webm','video/x-ms-wmv','video/quicktime'],
                'audio': ['audio/mp3','audio/x-ms-wma','audio/aac','audio/mpeg'],
                'archive': ['application/zip','application/gzip','application/x-rar-compressed','application/x-7z-compressed','application/x-zip-compressed'],
            };

            function getFileType(fileType) {
                return Object.keys(formats).filter(type => formats[type].includes(fileType));
            }

            // add saved file to list on page
            function generateFileHtml(file) {
                let filesize = Math.round( file.size / 1024 ),
                    filesize_label = ( filesize > 1000 ) ? 'mb' : 'kb',
                    icon = ( getFileType(file.type).length > 0 ) ? getFileType(file.type) : 'unknown',
                    icon_url = assignments_data.icon_url + icon + '.svg';
                filesize = ( filesize > 1000 ) ? Math.round( filesize / 1024 ) : filesize;
                let html = `
                    <div class="masterstudy-file-upload__item">
                        <img src="${icon_url}" class="masterstudy-file-upload__image">
                        <div class="masterstudy-file-upload__wrapper">
                            <span class="masterstudy-file-upload__title">${file.name}</span>
                            <span class="masterstudy-file-upload__size">${filesize} ${filesize_label}</span>
                            <a class="masterstudy-file-upload__link" href="#" data-id="${file.id}"></a>
                        </div>
                    </div>`;
                $('.masterstudy-file-upload__item-wrapper').append(html);
            }

            // delete file by click on trash icon in file container
            $(document).on('click', '.masterstudy-file-upload__link', function(e) {
                e.preventDefault();
                $("[data-id='assignment_file_alert']").addClass('masterstudy-alert_open');
                deleteFile($(this).data('id'));
            });

            function deleteFile(id) {
                $("[data-id='assignment_file_alert']").find("[data-id='submit']").click(function(e) {
                    const formData = new FormData();
                    formData.append('file_id', id);
                    formData.append('action', 'stm_lms_delete_assignment_file');
                    formData.append('nonce', assignments_data.delete_nonce);
                    $.ajax({
                        url: assignments_data.ajax_url,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        beforeSend: function() {
                            $("[data-id='assignment_file_alert']").removeClass('masterstudy-alert_open');
                        },
                        success: function (data) {
                            if (data === 'OK') {
                                $('[data-id=' + id + ']').closest('.masterstudy-file-upload__item').remove();
                            }
                        }
                    });
                });
            }

            //cancel alert for delete file
            $("[data-id='assignment_file_alert']").find("[data-id='cancel']").click(function(e) {
                e.preventDefault();
                $("[data-id='assignment_file_alert']").removeClass('masterstudy-alert_open');
            });
            $("[data-id='assignment_file_alert']").find('.masterstudy-alert__header-close').click(function(e) {
                e.preventDefault();
                $("[data-id='assignment_file_alert']").removeClass('masterstudy-alert_open');
            });

            // disable submit assignment button
            let submit_button = $('[data-id="masterstudy-course-player-assignments-send-button"]');
            submit_button.attr('disabled', 1);
            submit_button.addClass('masterstudy-button_disabled');

            if (typeof tinyMCE !== 'undefined') {
                getEditor();
            }

            // watch wp-editor changes, disable "submit" button if wp-editor is empty
            function getEditor() {
                let editor = tinyMCE.get(assignments_data.editor_id);
                if (editor.iframeElement === undefined) {
                    setTimeout(function () {
                        getEditor();
                    }, 500);
                } else {
                    content = editor.getContent();
                    editor.on('keyup', function (e) {
                        content = editor.getContent();
                        if (content.length > 0) {
                            submit_button.removeAttr('disabled');
                            submit_button.removeClass('masterstudy-button_disabled');
                        } else {
                            submit_button.attr('disabled', 1);
                            submit_button.addClass('masterstudy-button_disabled');
                        }
                    });
                }
            }
        }
    });
})(jQuery);