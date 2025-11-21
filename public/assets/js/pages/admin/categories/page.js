jQuery.index = {
    data: {
        table: null,
        searchInput: null,
        searchButton: null,
        statusFilter: 'all',
    },
    init: function () {
        var self = this;

        self.data.searchInput = $('#search-categories');
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
                url: '/admin/categories/request',
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
                    width: '10%',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: null,
                    className: 'text-center align-middle',
                    width: '10%',
                    render: function (data) {
                        if (data.thumbnail) {
                            return `
                                <div class='d-flex justify-content-center align-items-center'>
                                    <img src='/uploads/categories/${data.thumbnail}' alt='Thumbnail' class='rounded' width='150' height='150' style="object-fit: cover;"/>
                                </div>
                            `;
                        } else {
                            return `
                                <div class='d-flex justify-content-center align-items-center'>
                                    <span class='text-muted'>Tidak ada gambar</span>
                                </div>
                            `;
                        }
                    }
                },
                {
                    data: null,
                    className: 'text-left align-middle',
                    width: '30%',
                    render: function (data) {
                        return data.name;
                    }
                },
                {
                    data: null,
                    className: 'text-left align-middle',
                    width: '30%',
                    render: function (data) {
                        return data.description || 'Tidak ada deskripsi';
                    }
                },
                {
                    data: null,
                    className: 'text-center align-middle',
                    width: '10%',
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
                    width: '10%',
                    render: function (data) {
                        return `
                            <div class='d-grid gap-2'>
                                <button class='btn btn-sm btn-dark btn-edit w-100'
                                    data-id='${data.id}' data-name='${data.name}'>
                                    <span class="mdi mdi-pencil me-1 fs-18"></span>Ubah
                                </button>
                                <button class='btn btn-sm btn-danger btn-hapus w-100'
                                    data-id='${data.id}' data-name='${data.name}'>
                                    <span class="mdi mdi-delete me-1 fs-18"></span>Hapus
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            scrollY: '500px',
            scrollCollapse: true,
            paging: true,
            pageLength: 10,
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
                content: 'Apakah Anda yakin ingin menghapus kategori <strong>' + name + '</strong>? Data yang telah dihapus tidak dapat dikembalikan.',
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
            window.location.href = '/admin/categories/' + id + '/edit';
        });
    }
};

$(document).ready(function () {
    jQuery.index.init();
});
