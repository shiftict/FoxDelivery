<template xmlns="http://www.w3.org/1999/html">
    <div class="container-xxl" id="kt_content_container">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                        <span class="card-icon">
                        <span class="svg-icon svg-icon-primary svg-icon-2x ">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M10 10V9C10 8.4 10.4 8 11 8H21C21.6 8 22 8.4 22 9V10C22 10.6 21.6 11 21 11H11C10.4 11 10 10.6 10 10ZM3 6H21C21.6 6 22 5.6 22 5V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4V5C2 5.6 2.4 6 3 6Z" fill="black"/>
                            <path opacity="0.3" d="M2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21.6 16 21 16H3C2.4 16 2 15.6 2 15ZM11 21H21C21.6 21 22 20.6 22 20V19C22 18.4 21.6 18 21 18H11C10.4 18 10 18.4 10 19V20C10 20.6 10.4 21 11 21Z" fill="black"/>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                        </span>

                    <h3 class="card-label"> sssss</h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">
                        <!--begin::Filter-->
                        <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                                data-kt-menu-placement="bottom-end">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen031.svg-->
                            <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                             height="24px" viewBox="0 0 24 24" version="1.1">
														<g stroke="none" stroke-width="1" fill="none"
                                                           fill-rule="evenodd">
															<rect x="0" y="0" width="24" height="24"></rect>
															<path
                                                                d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                                                                fill="#000000" opacity="0.3"></path>
															<path
                                                                d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                                                                fill="#000000"></path>
														</g>
													</svg>
                                    </span>
                            <!--end::Svg Icon-->
<!--                            {{ $lang.components.buttons.extract }}-->
                        </button>
                        <!--begin::Menu 1-->
                        <div class="menu menu-sub menu-sub-dropdown w-200px w-md-225px" data-kt-menu="true">
                            <!--begin::Header-->
                            <div class="px-7 py-4">
                                <div class="fs-6 text-dark">
<!--                                    {{ $lang.components.datatable.dropdownTitle }}-->
                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Separator-->
                            <div class="separator border-gray-200"></div>
                            <!--end::Separator-->
                            <!--begin::Content-->
                            <div class="px-7 py-4">
                                <div class="menu-item px-3">
                                    <button class="btn btn-icon btn-link btn-color-success btn-active-color-primary"
                                            v-on:click="print"><i
                                        class="las la-print fs-2 me-2"></i> Print
                                    </button>
                                </div>
                                <div class="menu-item px-3">
                                    <button class="btn btn-icon btn-link btn-color-success btn-active-color-primary"
                                            v-on:click="copy"><i
                                        class="las la-copy fs-2 me-2"></i> Copy
                                    </button>
                                </div>
                                <div class="menu-item px-3">
                                    <button class="btn btn-icon btn-link btn-color-success btn-active-color-primary"
                                            v-on:click="exportExcel"><i
                                        class="las la-file-excel fs-2 me-2"></i> Excel
                                    </button>
                                </div>
                                <div class="menu-item px-3">
                                    <button class="btn btn-icon btn-link btn-color-success btn-active-color-primary"
                                            v-on:click="exportCSV"><i
                                        class="las la-file-csv fs-2 me-2"></i> CSV
                                    </button>
                                </div>
                                <div class="menu-item px-3">
                                    <button class="btn btn-icon btn-link btn-color-success btn-active-color-primary"
                                            v-on:click="exportPDF"><i
                                        class="las la-file-pdf fs-2 me-2"></i> PDF
                                    </button>
                                </div>
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Menu 1-->
                        <!--end::Filter-->
                    </div>
                    <!--end::Toolbar-->
                    <!--begin::Add customer-->
                    <!--end::Add customer-->
                    <!--begin::Button-->
                    <button class="btn btn-primary font-weight-bolder" style="display: flex;"
                            v-on:click="refreshDT">
                            <span class="svg-icon svg-icon-md svg-icon-lg-1">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M14.5 20.7259C14.6 21.2259 14.2 21.826 13.7 21.926C13.2 22.026 12.6 22.0259 12.1 22.0259C9.5 22.0259 6.9 21.0259 5 19.1259C1.4 15.5259 1.09998 9.72592 4.29998 5.82592L5.70001 7.22595C3.30001 10.3259 3.59999 14.8259 6.39999 17.7259C8.19999 19.5259 10.8 20.426 13.4 19.926C13.9 19.826 14.4 20.2259 14.5 20.7259ZM18.4 16.8259L19.8 18.2259C22.9 14.3259 22.7 8.52593 19 4.92593C16.7 2.62593 13.5 1.62594 10.3 2.12594C9.79998 2.22594 9.4 2.72595 9.5 3.22595C9.6 3.72595 10.1 4.12594 10.6 4.02594C13.1 3.62594 15.7 4.42595 17.6 6.22595C20.5 9.22595 20.7 13.7259 18.4 16.8259Z" fill="black"/>
                                <path opacity="0.3" d="M2 3.62592H7C7.6 3.62592 8 4.02592 8 4.62592V9.62589L2 3.62592ZM16 14.4259V19.4259C16 20.0259 16.4 20.4259 17 20.4259H22L16 14.4259Z" fill="black"/>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        تحديث
                    </button>
                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <div class="py-5">
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-stack mb-5">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->

                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546"
                                              height="2" rx="1"
                                              transform="rotate(45 17.0365 15.1223)"
                                              fill="black"></rect>
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="black"></path>
                                    </svg>
                                </span>
                            <!--end::Svg Icon-->
                            <input type="text" data-kt-docs-table-filter="بحث"
                                   v-model="searchValue"
                                   v-on:keyup="search()"
                                   class="form-control form-control-solid w-250px ps-15"
                                   placeholder="بحث">
                        </div>
                        <!--end::Search-->
                        <!--begin::Toolbar-->
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Datatable-->
                    <data-table api="/api/v1/datatable/agendas"
                                :columns='columns'
                                :filters='filters'
                                toggleName="is_active"
                                toggleActive="1"
                                toggleDisActive="0"
                                ref="dataTable">
                        <tr slot="header">
                            <th>قق</th>
                            <th>ثثثثثثثثثث</th>
                            <th>صص</th>
                            <th>ييييييي</th>
                            <th>سسسسس</th>
                            <th>سس</th>
                            <th>اكشن</th>
                        </tr>
                    </data-table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['url', 'users'],
    data() {
        return {
            inProgress: false,
            groups: [],
            columns: [
                {data: 'year', name: 'year'},
                {data: 'start_date', name: 'start_date'},
                {data: 'end_date', name: 'end_date'},
                {data: 'first_quarter_date', name: 'first_quarter_date'},
                {data: 'second_quarter_date', name: 'second_quarter_date'},
                {data: 'third_quarter_date', name: 'third_quarter_date'},
                {data: 'action', orderable: false, searchable: false}
            ],
            filters: {},
            searchValue: ''
        }
    },
    created() {
        this.$lang.setLang(window.language);
        let self = this;
    },
    updated() {

    },
    mounted() {
    },
    methods: {
        refreshDT() {
            this.$refs.dataTable.refreshDT();
        },
        print() {
            this.$refs.dataTable.print();
        },
        exportPDF() {
            this.$refs.dataTable.exportPDF();
        },
        exportExcel() {
            this.$refs.dataTable.exportExcel();
        },
        exportCSV() {
            this.$refs.dataTable.exportCSV();
        },
        copy() {
            this.$refs.dataTable.copy();
        },
        clearErrors() {
            this.form.errors.clear();
        },
        resetForm() {
            this.form.reset();
            this.clearErrors();
        },
        search() {
            this.$refs.dataTable.generalSearch(this.searchValue);
        },
        customFilters() {
            this.$refs.dataTable.customFilters()
        }
    },
}
</script>
<style scoped>
</style>

