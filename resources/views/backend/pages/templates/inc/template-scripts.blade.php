<script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
<script>
    "use strict";

    var streamline = '{{ getSetting('enable_streamline') }}';

    // init hljs
    function initHljs() {
        hljs.highlightAll();
        hljs.initLineNumbersOnLoad();
    }
    // show hide templates optional field
    $(document).ready(function() {
        $("#tt-advance-options").hide();
        $(".tt-advance-options").on("click", function(e) {
            $("#tt-advance-options").slideToggle(300);
        });
        initHljs();
        let projectEditRoute = '{{ Route::is('projects.edit') }}';
        if (projectEditRoute != 1) {
            $('.editor').summernote('disable');
        }
    });

    // showSaveToFolderModal
    function showSaveToFolderModal() {

        let project_id = $('.project_id').val();

        if (project_id == null || project_id == '') {
            notifyMe('error', '{{ localize('Please generate AI contents first') }}');
            return;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            method: 'POST',
            url: '{{ route('projects.moveToFolderModal') }}',
            data: {
                project_id
            },
            beforeSend: function() {
                $('.move_to_folder_btn').prop('disabled', true);
            },
            complete: function() {
                $('.move_to_folder_btn').prop('disabled', false);
            },
            success: function(data) {
                if (data.status == 200) {
                    $('.move-to-folder-contents').html(data.contents);
                    $('.modalSelect2').select2({
                        dropdownParent: $(('.modalParentSelect2'))
                    });
                    $('#saveToFolder').modal('show');
                    moveToFolderFormInit();
                } else {
                    notifyMe('error', '{{ localize('Something went wrong') }}');
                }
            },
            error: function(data) {
                notifyMe('error', '{{ localize('Something went wrong') }}');
            }
        });
    }

    // move-to-folder-form
    function moveToFolderFormInit() {
        $('.move-to-folder-form').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                method: 'POST',
                url: '{{ route('projects.moveToFolder') }}',
                data: form.serialize(),
                beforeSend: function() {
                    $('.move-project-btn').prop('disabled', true);
                },
                complete: function() {
                    $('.move-project-btn').prop('disabled', false);
                },
                success: function(data) {
                    if (data.status == 200) {
                        $('#saveToFolder').modal('hide');
                        notifyMe('success', '{{ localize('Project moved successfully') }}');
                    } else {
                        notifyMe('error', '{{ localize('Something went wrong') }}');
                    }
                },
                error: function(data) {
                    notifyMe('error', '{{ localize('Something went wrong') }}');
                }
            });
        });
    }

    function initJqueryEvents() {

        // contents start
        // copy contents 
        $(".copyBtn").on("click", function() {
            var type = $(this).data('type');
            if (type && type == "code") {
                var html = document.querySelector('#codetext');
                var msg = '{{ localize('Code has been copied successfully') }}';
            } else {
                var html = document.querySelector('.note-editable');
                var msg = '{{ localize('Content has been copied successfully') }}';
            }
            const selection = window.getSelection();
            const range = document.createRange();
            range.selectNodeContents(html);
            selection.removeAllRanges();
            selection.addRange(range);
            document.execCommand('copy');
            window.getSelection().removeAllRanges()
            notifyMe('success', msg);
        });

        // create contents ajax call
        $('.generate-contents-form').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let url = $(this).data('url');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                method: 'POST',
                url: url,
                data: form.serialize(),
                beforeSend: function() {
                    $('.btn-create-text').html(TT.localize.pleaseWait);
                    $('.btn-create-content').prop('disabled', true);
                    $('.btn-create-content .tt-text-preloader').removeClass('d-none');
                },
                complete: function() {
                    $('.btn-create-text').html(TT.localize.createContent);
                    $('.btn-create-content').prop('disabled', false);
                    $('.btn-create-content .tt-text-preloader').addClass('d-none');
                    $('.editor').summernote('enable');
                },
                success: function(data) {
                    if (data.status == 200) {
                        $('.used-words-percentage').empty();

                        if (parseInt(streamline) == 1) {
                            new Typed('.note-editable', {
                                strings: [data['output']],
                                typeSpeed: 10,
                            });
                        } else {
                            $('.note-editable').html(data['output']);
                        }

                        $('.project-title').val(data.title);
                        $('.project_id').val(data.project_id);
                        $('.used-words-percentage').append(data.usedPercentage);

                        notifyMe('success', '{{ localize('Contents generated successfully') }}');
                    } else {
                        if (data.message) {
                            notifyMe('error', data.message);
                        } else {
                            notifyMe('error', '{{ localize('Something went wrong') }}');
                        }
                    }
                },
                error: function(data) {
                    if (data.status == 400 && data.message) {
                        notifyMe('error', data.message);
                    } else {
                        notifyMe('error', '{{ localize('Something went wrong') }}');
                    }
                }
            });
        });

        // content-form submit -- update contents
        $('.content-form').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);

            let project_id = $('.project_id').val();

            if (project_id == null || project_id == '') {
                notifyMe('error', '{{ localize('Please generate AI contents first') }}');
                return;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                method: 'POST',
                url: '{{ route('projects.update') }}',
                data: form.serialize(),
                beforeSend: function() {
                    $('.content-form-submit').prop('disabled', true);
                },
                complete: function() {
                    $('.content-form-submit').prop('disabled', false);
                },
                success: function(data) {
                    if (data.status == 200) {
                        notifyMe('success', '{{ localize('Contents updated successfully') }}');
                    } else {
                        notifyMe('error', '{{ localize('Something went wrong') }}');
                    }
                },
                error: function(data) {
                    notifyMe('error', '{{ localize('Something went wrong') }}');
                }
            });
        });

        // favorite template 
        $(".favorite-template").each(function(el) {
            var $this = $(this);
            let templateId = $this.data('template');
            $this.on('click', function() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    method: 'POST',
                    url: '{{ route('templates.updateFavorite') }}',
                    data: {
                        templateId: templateId
                    },
                    success: function(data) {
                        $($this).find('i').toggleClass('lar').toggleClass('las')
                            .toggleClass(
                                'text-success');
                        notifyMe('success', data['message']);
                    },
                    error: function(data) {
                        notifyMe('error', '{{ localize('Something went wrong') }}');
                    }
                });
            })
        });
        // contents ends

        // create images ajax call
        $('.generate-images-form').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                method: 'POST',
                url: '{{ route('images.generate') }}',
                data: form.serialize(),
                beforeSend: function() {
                    $('.btn-create-content').html(TT.localize.pleaseWait);
                    $('.btn-create-content').prop('disabled', true);
                },
                complete: function() {
                    $('.btn-create-content').prop('disabled', false);
                    $('.btn-create-content').html(TT.localize.generateImage);
                },
                success: function(data) {
                    if (data.status == 200) {
                        $('.used-words-percentage').empty();
                        $('.ai-images-wrapper').empty();

                        $('.used-words-percentage').append(data.usedPercentage);
                        $('.ai-images-wrapper').append(data.images);

                        $('[data-bs-toggle="tooltip"]').tooltip();
                        $(".confirm-delete").click(function(e) {
                            e.preventDefault();
                            var url = $(this).data("href");
                            $("#delete-modal").modal("show");
                            $("#delete-link").attr("href", url);
                        });

                        magnifyPopup();

                        initFeather();
                        notifyMe('success', '{{ localize('Image generated successfully') }}');
                    } else {
                        if (data.message) {
                            notifyMe('error', data.message);
                        } else {
                            notifyMe('error', '{{ localize('Something went wrong') }}');
                        }
                    }
                },
                error: function(data) {
                    if (data.status == 400 && data.message) {
                        notifyMe('error', data.message);
                    } else {
                        notifyMe('error', '{{ localize('Something went wrong') }}');
                    }
                }
            });
        });

        // create code ajax call
        $('.generate-codes-form').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                method: 'POST',
                url: '{{ route('codes.generate') }}',
                data: form.serialize(),
                beforeSend: function() {
                    $('.btn-create-text').html(TT.localize.pleaseWait);
                    $('.btn-create-content').prop('disabled', true);
                    $('.btn-create-content .tt-text-preloader').removeClass('d-none');
                },
                complete: function() {
                    $('.btn-create-text').html(TT.localize.generateCode);
                    $('.btn-create-content').prop('disabled', false);
                    $('.btn-create-content .tt-text-preloader').addClass('d-none');
                },
                success: function(data) {
                    if (data.status == 200) {
                        $('.used-words-percentage').empty();
                        $('.content-code-card').empty()

                        $('.content-code-card').html(data['output']);

                        initJqueryEvents();

                        initHljs();

                        $('.used-words-percentage').append(data.usedPercentage);
                        notifyMe('success', '{{ localize('Code generated successfully') }}');
                    } else {
                        if (data.message) {
                            notifyMe('error', data.message);
                        } else {
                            notifyMe('error', '{{ localize('Something went wrong') }}');
                        }
                    }
                },
                error: function(data) {
                    if (data.status == 400 && data.message) {
                        notifyMe('error', data.message);
                    } else {
                        notifyMe('error', '{{ localize('Something went wrong') }}');
                    }
                }
            });
        });

        // create s2t ajax call
        $('.generate-s2t-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                method: 'POST',
                url: '{{ route('s2t.generate') }}',
                dataType: "JSON",
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('.btn-create-text').html(TT.localize.pleaseWait);
                    $('.btn-create-content').prop('disabled', true);
                    $('.btn-create-content .tt-text-preloader').removeClass('d-none');
                },
                complete: function() {
                    $('.btn-create-text').html(TT.localize.createContent);
                    $('.btn-create-content').prop('disabled', false);
                    $('.btn-create-content .tt-text-preloader').addClass('d-none');
                    $('.editor').summernote('enable');
                },
                success: function(data) {
                    if (data.status == 200) {
                        $('.used-words-percentage').empty();

                        if (parseInt(streamline) == 1) {
                            new Typed('.note-editable', {
                                strings: [data['output']],
                                typeSpeed: 10,
                            });
                        } else {
                            $('.note-editable').html(data['output']);
                        }

                        $('.project-title').val(data.title);
                        $('.project_id').val(data.project_id);
                        $('.used-words-percentage').append(data.usedPercentage);
                        notifyMe('success', '{{ localize('Contents generated successfully') }}');
                    } else {
                        if (data.message) {
                            notifyMe('error', data.message);
                        } else {
                            notifyMe('error', '{{ localize('Something went wrong') }}');
                        }
                    }
                },
                error: function(data) {
                    if (data.status == 400 && data.message) {
                        notifyMe('error', data.message);
                    } else {
                        notifyMe('error', '{{ localize('Something went wrong') }}');
                    }
                }
            });
        });
    }
    initJqueryEvents();
</script>
