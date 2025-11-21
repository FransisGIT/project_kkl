jQuery.index = {
    data: {
        table: null,
        searchInput: null,
        searchButton: null
    },
    init: function () {
        var self = this;

        self.data.searchInput = $('#search-sosialmedia');
        self.data.searchButton = $('.btn-search');

        self.initTable();
        self.setEvents();
    },
    initTable: function () {
        var self = this;

        self.data.table = $('#ohmytable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/admin/pages/social-media/request',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (d) {
                    d.search = {
                        value: self.data.searchInput.val(),
                        regex: false
                    };

                    return d;
                }
            },
            columns: [
                {
                    data: null,
                    className: 'text-center fw-bold align-middle',
                    width: '5%',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: null,
                    className: 'text-start align-middle',
                    width: '20%',
                    render: function (data) {
                        return data.platform.charAt(0).toUpperCase() + data.platform.slice(1);
                    }
                },
                {
                    data: null,
                    className: 'text-start align-middle text-truncate',
                    width: '50%',
                    render: function (data) {
                        return `<a href="${data.url}" target="_blank" title="${data.url}">${data.url}</a>`;
                    }
                },
                {
                    data: null,
                    className: 'text-center align-middle',
                    width: '15%',
                    orderable: false,
                    render: function (data) {
                        return `
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-dark btn-edit"
                                    data-id="${data.id}" title="Ubah">
                                    <i class="mdi mdi-pencil fs-18"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-hapus"
                                    data-id="${data.id}" data-name="${data.platform}" title="Hapus">
                                    <i class="mdi mdi-delete fs-18"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            scrollY: '500px',
            scrollCollapse: true,
            paging: true,
            pageLength: 6,
            lengthChange: false,
            searching: false,
            ordering: false,
            autoWidth: false,
            language: {
                emptyTable: "Tidak ditemukan data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                search: "Cari:",
                paginate: {
                    next: "Next",
                    previous: "Prev"
                }
            }
        });
    },
    setEvents: function () {
        var self = this;

        self.data.searchButton.on('click', function () {
            var button = $(this);
            button.attr('data-kt-indicator', 'on');
            button.prop('disabled', true);

            self.data.table.ajax.reload(function () {
                button.removeAttr('data-kt-indicator');
                button.prop('disabled', false);
            });
        });

        self.data.searchInput.keyup(function (e) {
            if (e.keyCode === 13) {
                self.data.searchButton.click();
            }
        });

        $("#ohmytable").on('click', "button.btn-hapus", function () {
            var id = $(this).data("id");
            var name = $(this).data("name");

            $.confirm({
                title: 'Konfirmasi Hapus',
                type: 'orange',
                columnClass: 'small',
                content: 'Yakin ingin menghapus sosial media <strong>' + name + '</strong>? Tindakan ini tidak dapat dibatalkan.',
                buttons: {
                    cancel: {
                        text: 'Tidak',
                        btnClass: 'btn-red',
                        keys: ['esc']
                    },
                    confirm: {
                        text: 'Yakin',
                        btnClass: 'btn-green',
                        keys: ['enter'],
                        action: function () {
                            $("#id_delete").val(id)
                            $("#form_delete").submit();
                        }
                    }
                }
            });
        });

        $(document).on('click', '.btn-edit', function () {
            var row = jQuery.index.data.table.row($(this).parents('tr')).data();
            console.log(row);

            $('#platform').val(row.platform).trigger('change');
            $('#url').val(row.url);

            $('#tag-form').attr('action', '/admin/pages/social-media/update');

            if ($('#social_media_id').length === 0) {
                $('#tag-form').append('<input type="hidden" name="id" id="social_media_id" value="' + row.id + '">');
            } else {
                $('#social_media_id').val(row.id);
            }

            $('#btn-cancel').removeClass('d-none');
            $('#btn-submit').text('Simpan').removeClass('btn-primary').addClass('btn-success');
        });

        $('#btn-cancel').on('click', function () {
            $('#social_media_id').remove();
            $('#platform').val(null).trigger('change');
            $('#url').val('');

            $('#tag-form').attr('action', '/admin/pages/social-media/store');
            $('#btn-submit').text('Tambah').removeClass('btn-success').addClass('btn-primary');

            $(this).addClass('d-none');
        });
    }
};

$(document).ready(function () {
    jQuery.index.init();
});
