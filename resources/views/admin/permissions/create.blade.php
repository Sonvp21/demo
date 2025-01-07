<x-admin-layout>
    <div class="flex-grow w-full p-5">
        <div class="mb-5">
            <h1 class="text-2xl font-semibold text-gray-800">Thêm quyền</h1>
            <nav class="mt-2">
                <ol class="breadcrumb flex text-sm text-gray-600 space-x-2">
                    <li class="breadcrumb-item">
                        <a>Tài khoản</a>
                    </li>
                    <li class="breadcrumb-item text-gray-500">
                        <span>/</span>
                    </li>
                    <li class="breadcrumb-item active text-gray-800 font-medium">
                        Thêm quyền
                    </li>
                </ol>
            </nav>
        </div>
        <x-admin.alerts.toast/>

        <div class="overflow-x-auto bg-white rounded-lg mt-5">
            <div class="overflow-x-auto p-5 text-center">
                <form action="{{ route('admin.permissions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <select name="module_parent" class="select select-success w-full max-w-xs">
                            <option value="">Chọn tên module</option>
                            @foreach (config('permissions.table_module') as $moduleItem)
                                <option value="{{ $moduleItem }}">
                                    {{ __('permissions.table_module.' . $moduleItem) }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group flex place-content-center mt-5">
                        @foreach (config('permissions.module_childrent') as $moduleItemChildrent)
                            <label>
                                <input name="module_childrent[]" type="checkbox" class="checkbox checkbox-accent m-2"
                                    value="{{ $moduleItemChildrent }}">
                                {{ __('permissions.module_childrent.' . $moduleItemChildrent) }}
                            </label>
                        @endforeach
                    </div>
                    <div class="flex justify-center pb-3">
                        <button type="submit" class="btn btn-outline btn-accent !min-h-9 h-9 w-fit mt-2 mx-4">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @pushonce('bottom_scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            var jq = $.noConflict();
            jq(document).ready(function() {
                jq('.checkbox_wrapper').on('click', function() {
                    jq(this).parents('.card').find('.checkbox_childrent').prop('checked', jq(this).prop('checked'));
                });

                jq('.checkall').on('click', function() {
                    jq(this).parents().find('.checkbox_childrent').prop('checked', jq(this).prop('checked'));
                    jq(this).parents().find('.checkbox_wrapper').prop('checked', jq(this).prop('checked'));
                });
            });
        </script>
    @endpushonce
</x-admin-layout>
