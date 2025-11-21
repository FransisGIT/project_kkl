jQuery.index = {
    data: {
        table: null,
        searchInput: null,
        searchButton: null,
        statusFilter: 'all',
    },
    init: function () {
        var self = this;

        self.data.searchInput = $('#search-tags');
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
                url: '/admin/articles/tags/request',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (d) {
                    d.search = {
                        value: self.data.searchInput.val(),
                        regex: false
                    };
                    d.status_filter = self.data.statusFilter;
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
                    className: 'text-left align-middle',
                    width: '35%',
                    render: function (data) {
                        return data.name;
                    }
                },
                {
                    data: null,
                    className: 'text-left align-middle',
                    width: '25%',
                    render: function (data) {
                        return data.slug;
                    }
                },
                {
                    data: null,
                    className: 'text-center align-middle',
                    width: '15%',
                    render: function (data) {
                        if (data.status === 'aktif') {
                            return 'Aktif';
                        } else {
                            return 'Tidak Aktif';
                        }
                    }
                },
                {
                    data: null,
                    className: 'text-center align-middle fw-bold',
                    width: '20%',
                    render: function (data) {
                        return `
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-dark btn-edit"
                                    data-id="${data.id}" data-name="${data.name}" title="Ubah">
                                    <i class="mdi mdi-pencil fs-18"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-hapus"
                                    data-id="${data.id}" data-name="${data.name}" title="Hapus">
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
            pageLength: 3,
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

        $('.filter-status').on('click', function (e) {
            e.preventDefault();
            self.data.statusFilter = $(this).data('status');
            console.log('Status Filter:', self.data.statusFilter);
            self.data.table.ajax.reload();
        });

        $("#ohmytable").on('click', "button.btn-hapus", function () {
            var id = $(this).data("id");
            var name = $(this).data("name");

            $.confirm({
                title: 'Konfirmasi Hapus',
                type: 'orange',
                columnClass: 'small',
                content: 'Apakah Anda yakin ingin menghapus penanda <strong>' + name + '</strong>? Data yang telah dihapus tidak dapat dikembalikan lagi.',
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
            var id = $(this).data('id');

            var row = jQuery.index.data.table.row($(this).parents('tr')).data();

            $('#tag_id').val(id);
            $('#name').val(row.name);
            $('#status').val(row.status);

            $('#tag-form').attr('action', '/admin/articles/tags/update');
            $('#btn-cancel').removeClass('d-none');
            $('#btn-submit').text('Simpan').removeClass('btn-primary').addClass('btn-primary');
        });

        $('#btn-cancel').on('click', function () {
            $('#tag_id').val('');
            $('#name').val('');
            $('#status').val('');
            $('#tag-form').attr('action', '/admin/articles/tags/store');
            $('#btn-submit').text('Tambah').removeClass('btn-success').addClass('btn-primary');
            $(this).addClass('d-none');
        });

    }
};

$(document).ready(function () {
    jQuery.index.init();
});
