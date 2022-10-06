<template>
    <!--begin::Datatable-->
    <table class="table table-striped- table-bordered table-hover table-checkable responsive no-wrap" id="data_table">
        <thead>
        <slot name="header"></slot>
        </thead>
        <tbody>

        </tbody>
    </table>
    <!--end::Datatable-->
</template>

<script>
export default {
    props: ['api', 'columns', 'toggleName', 'toggleActive', 'toggleDisActive', 'filters', 'order'],
    data() {
        return {
            dtHandle: null,
            filtered: {},
            toggleNameLabel: 'status',
            toggleActiveLabel: 'active',
            toggleDisActiveLabel: 'dis_active',
        }
    },
    mounted() {
        if (this.toggleName) {
            this.toggleNameLabel = this.toggleName;
        }
        if (this.toggleActive) {
            this.toggleActiveLabel = this.toggleActive;
        }
        if (this.toggleDisActive) {
            this.toggleDisActiveLabel = this.toggleDisActive;
        }
        let self = this;
        let token = document.head.querySelector('meta[name="csrf-token"]');
        this.dtHandle = $(this.$el).DataTable({
            language: {
                "url": "/assets/metronic/plugins/custom/datatables/lang/" + window.language + ".json"
            },
            buttons: [
                'copy',
                'excel',
                'csv',
                'pdfHtml5',
                {
                    extend: 'print',
                    customize: function ( win ) {
                        $(win.document.body).css('direction', 'rtl');
                    }
                }
            ],
            responsive: true,
            searchDelay: 500,
            processing: true,
            serverSide: true,
            ajax: {
                'url': this.api,
                'type': 'GET',
                'data': function (d) {
                    for (let k in self.filtered) d[k] = self.filtered[k];
                },
                'beforeSend': function (request) {
                    request.setRequestHeader("X-CSRF-TOKEN", token.content);
                    request.setRequestHeader("X-Requested-With", 'XMLHttpRequest');
                    request.setRequestHeader("X-localization", window.language);
                },
            },
            columns: this.columns,
            order: this.order,
            fnDrawCallback: function () {
                // init the dropdown menus
                KTMenu.createInstances();
                //tooltips run
                $("[data-toggle=tooltip]").tooltip();
                // status button
                $(".my-dt-state-btn").on('click', function () {
                    let btn = $(this);
                    let id = $(this).data('value');
                    let api = $(this).data('api');
                    let data = {id: id, _method: 'PUT'};
                    btn.children('i').removeAttr('class');
                    btn.children('i').addClass('fas fa-spinner fa-spin');
                    axios.post(api, data)
                        .then(response => {
                            if (response.data.data[self.toggleNameLabel].toString() == self.toggleActiveLabel.toString()) {
                                btn.removeClass('btn-danger');
                                btn.removeClass('btn-dark');
                                btn.addClass('btn-success');
                                btn.children('i').removeClass('fas fa-spinner fa-spin');
                                btn.children('i').addClass('bi bi-patch-check-fill fs-1');
                            } else if (response.data.data[self.toggleNameLabel].toString() == self.toggleDisActiveLabel.toString()) {
                                btn.removeClass('btn-success');
                                btn.removeClass('btn-danger');
                                btn.addClass('btn-dark');
                                btn.children('i').removeClass('fas fa-spinner fa-spin');
                                btn.children('i').addClass('bi bi-patch-minus-fill fs-1');
                            }
                        })
                        .catch(e => {
                            btn.removeClass('btn-success');
                            btn.removeClass('btn-dark');
                            btn.addClass('btn-danger');
                            btn.children('i').removeClass('fas fa-spinner fa-spin');
                            btn.children('i').addClass('bi bi-patch-exclamation-fill fs-1');
                        });
                    //end ajax
                });
                $('.my-dt-confirm').on("click", function () {
                    let id = $(this).data('val');
                    let api = $(this).data('api');
                    swal.fire({
                        title: self.$lang.messages.confirm,
                        text: self.$lang.messages.confirmDescription,
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: self.$lang.buttons.delete,
                        cancelButtonText: self.$lang.buttons.cancel,
                    }).then(function (result) {
                        if (result.value) {
                            axios.delete(api + '/' + id)
                                .then(response => {
                                    swal.fire(self.$lang.messages.success, self.$lang.messages.successDeleteMessage, "success");
                                    self.dtHandle.ajax.reload();
                                })
                                .catch(e => {
                                    swal.fire(self.$lang.messages.cancel, self.$lang.messages.error, "error");
                                })
                        } else if (result.dismiss === 'cancel') {
                            swal.fire(self.$lang.messages.cancel, self.$lang.messages.cancelDeleteMessage, "error");
                        }
                    });
                });
                $('.my-modal').on("click", function () {
                    let id = $(this).data('val');
                    self.getItemId(id);
                });
            },
        });
        //
    },
    updated() {
    },
    methods: {
        refreshDT: function () {
            this.dtHandle.ajax.reload();
        },
        drawDT: function () {
            this.dtHandle.draw();
        },
        print: function () {
            this.dtHandle.button('4').trigger();
        },
        exportPDF: function () {
            this.dtHandle.button('3').trigger();
        },
        exportCSV: function () {
            this.dtHandle.button('2').trigger();
        },
        exportExcel: function () {
            this.dtHandle.button('1').trigger();
        },
        copy: function () {
            this.dtHandle.button('0').trigger();
        },
        search: function () {
            let params = {};
            let self = this;
            $('.datatable-input').each(function () {
                let i = $(this).data('col-index');
                if (params[i]) {
                    params[i] += '|' + $(this).val();
                } else {
                    params[i] = $(this).val();
                }
            });
            $.each(params, function (i, val) {
                // apply search params to datatable
                self.dtHandle.column(i).search(val ? val : '', false, false);
            });
            this.dtHandle.table().draw();
        },
        generalSearch(value) {
            this.dtHandle.search(value).draw();
        },
        customFilters() {
            let params = {};
            let self = this;
            $('.datatable-custom-filter').each(function () {
                let i = $(this).data('col-index');
                if ($(this).is(':radio') || $(this).is(':checkbox')) {
                    if ($(this).is(":checked")) { // check if the radio is checked
                        if (params[i]) {
                            params[i] += '|' + $(this).val();
                        } else {
                            params[i] = $(this).val();
                        }
                    }
                    console.log(params)
                } else {
                    if (params[i]) {
                        params[i] += '|' + $(this).val();
                    } else {
                        params[i] = $(this).val();
                    }
                }
            });
            $.each(params, function (i, val) {
                // apply search params to datatable
                self.dtHandle.column(i).search(val ? '[' + val + ']' : '', false, false);
            });
            this.dtHandle.table().draw();
        },
        reset: function () {
            let self = this;
            $('.datatable-input').each(function () {
                $(this).val('');
                self.dtHandle.column($(this).data('col-index')).search('', false, false);
            });
            this.dtHandle.table().draw();
        },
        getItemId(uuid) {
            this.$emit('onEdit', uuid)
        },
    },
    watch: {
        uuid: function (newVal) {
            this.getItemId(newVal);
        },
        filters: {
            handler: function (val) {
                this.filtered = val;
                this.refreshDT();
            },
            deep: true
        },
        api: function (val) {
            this.drawDT()
        }
    }

}
</script>
