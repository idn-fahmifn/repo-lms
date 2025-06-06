@extends('layouts.admin')

@push('title', get_phrase('Subjects'))

@push('meta')
@endpush

@push('css')
@endpush



@section('content')
    <div class="ol-card radius-8px">
        <div class="ol-card-body my-3 py-12px px-20px">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-md-nowrap">
                <h4 class="title fs-16px">
                    <i class="fi-rr-settings-sliders me-2"></i>
                    {{ get_phrase('Tutor subjects') }} <span class="text-muted">({{ $subjects->count() }})</span>
                </h4>

                <a onclick="ajaxModal('{{ route('modal', ['admin.tutor_booking.subject_add']) }}', '{{ get_phrase('Add new subject') }}')" href="#" class="btn ol-btn-outline-secondary d-flex align-items-center cg-10px">
                    <span class="fi-rr-plus"></span>
                    <span>{{ get_phrase('Add new subject') }}</span>
                </a>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xl-7">
            <div class="ol-card p-4">
                <div class="ol-card-body">
                    <div class="col-lg-12">

                        @if ($subjects->count() > 0)
                            <div class="admin-tInfo-pagi d-flex justify-content-md-between justify-content-center align-items-center gr-15 flex-wrap">
                                <p class="admin-tInfo">
                                    {{ get_phrase('Showing') . ' ' . count($subjects) . ' ' . get_phrase('of') . ' ' . $subjects->total() . ' ' . get_phrase('data') }}
                                </p>
                            </div>
                            <div class="table-responsive package_list overflow-auto" id="package_list">
                                <table class="eTable eTable-2 print-table table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">{{ get_phrase('Name') }}</th>
                                            <th scope="col" class="text-center">{{ get_phrase('Status') }}</th>
                                            <th scope="col" class="print-d-none text-center">{{ get_phrase('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subjects as $key => $subject)
                                            <tr>
                                                <th scope="row">
                                                    <p class="row-number">{{ ++$key }}</p>
                                                </th>

                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="ms-1 mt-1">
                                                            <h4 class="title fs-14px">
                                                                <a href="">
                                                                    {{ ucfirst($subject->name) }}
                                                                </a>
                                                            </h4>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="sub-title2 text-12px text-center">
                                                        @if($subject['status'] == 1)
                                                            <p>{{ get_phrase('Active') }} </p>
                                                        @else
                                                            <p>{{ get_phrase('Deactive') }} </p>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="print-d-none text-center">
                                                    <div class="dropdown ol-icon-dropdown ol-icon-dropdown-transparent d-flex justify-content-center">
                                                        <button class="btn ol-btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <span class="fi-rr-menu-dots-vertical"></span>
                                                        </button>

                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a href="#" class="dropdown-item" onclick="ajaxModal('{{ route('modal', ['admin.tutor_booking.subject_edit', 'id' => $subject->id]) }}', '{{ get_phrase('Edit subject') }}')"> {{ get_phrase('Edit') }}</a>
                                                            </li>
                                                            <li>
                                                                @if($subject['status'] == 1)
                                                                    <a class="dropdown-item" href="{{ route('admin.tutor_subject_status', ['id' => $subject->id, 'status' => 'deactive']) }}">{{ get_phrase('Deactive') }}</a>
                                                                @else
                                                                    <a class="dropdown-item" href="{{ route('admin.tutor_subject_status', ['id' => $subject->id, 'status' => 'active']) }}">{{ get_phrase('Active') }}</a>
                                                                @endif
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" onclick="confirmModal('{{ route('admin.tutor_subject_delete', $subject->id) }}')">{{ get_phrase('Delete') }}</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="admin-tInfo-pagi d-flex justify-content-md-between justify-content-center align-items-center gr-15 flex-wrap">
                                <p class="admin-tInfo">
                                    {{ get_phrase('Showing') . ' ' . count($subjects) . ' ' . get_phrase('of') . ' ' . $subjects->total() . ' ' . get_phrase('data') }}
                                </p>
                                {{ $subjects->links() }}
                            </div>
                        @else
                            @include('admin.no_data')
                        @endif


                    </div>
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
@endsection
