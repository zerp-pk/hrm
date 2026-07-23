<?php

namespace Zerp\Hrm\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zerp\Hrm\Http\Requests\Api\StoreLeaveRequest;
use Zerp\Hrm\Http\Resources\LeaveApplicationResource;
use Zerp\Hrm\Models\Holiday;
use Zerp\Hrm\Models\LeaveApplication;
use Zerp\Hrm\Models\LeaveType;

class LeaveApiController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            if (Auth::user()->can('manage-leave-applications')) {
                $leaveapplications = LeaveApplication::query()
                    ->where(function ($q) {
                        if (Auth::user()->can('manage-any-leave-applications')) {
                            $q->where('created_by', creatorId());
                        } elseif (Auth::user()->can('manage-own-leave-applications')) {
                            $q->where('creator_id', Auth::id())->orWhere('employee_id', Auth::id());
                        } else {
                            $q->whereRaw('1 = 0');
                        }
                    })
                    ->when(request('reason'), function ($q) {
                        $q->where(function ($query) {
                            $query
                                ->where('reason', 'like', '%' . request('reason') . '%')
                                ->orWhereHas('employee', function ($subQuery) {
                                    $subQuery->where('name', 'like', '%' . request('reason') . '%');
                                })
                                ->orWhereHas('leave_type', function ($subQuery) {
                                    $subQuery->where('name', 'like', '%' . request('reason') . '%');
                                });
                        });
                    })
                    ->latest()
                    ->paginate(request('per_page', 10))
                    ->withQueryString();

                $leaveapplications->getCollection()->transform(
                    fn ($leave) => (new LeaveApplicationResource($leave))->resolve()
                );

                return $this->paginatedResponse($leaveapplications, 'Leave applications retrieved successfully');
            } else {
                return $this->errorResponse('Something went wrong');
            }
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong');
        }
    }

    public function store(StoreLeaveRequest $request)
    {
        try {
            if (Auth::user()->can('create-leave-applications')) {

                $validated = $request->validated();
                $employeeId = Auth::id();

                    // Validate working day, leave, and holiday
                $today     = now()->toDateString();
                $creatorId = creatorId();

                $workingDays      = getCompanyAllSetting($creatorId)['working_days'] ?? '';
                $workingDaysArray = json_decode($workingDays, true) ?? [];
                $isWorkingDay     = in_array(now()->dayOfWeek, $workingDaysArray);

                $isHoliday = Holiday::where('created_by', $creatorId)
                    ->where('start_date', '<=', $today)
                    ->where('end_date', '>=', $today)
                    ->exists();

                if (!$isWorkingDay) {
                    return $this->errorResponse('Leave cannot be created for non-working days.');
                }

                if ($isHoliday) {
                    return $this->errorResponse('Leave cannot be created on holidays.');
                }

                    // Calculate total days automatically
                $startDate = new \DateTime($validated['start_date']);
                $endDate   = new \DateTime($validated['end_date']);
                $totalDays = $startDate->diff($endDate)->days + 1;

                    // Get leave type details
                $leaveType = LeaveType::find($validated['leave_type_id']);
                if (!$leaveType) {
                    return $this->errorResponse('Invalid leave type selected.');
                }

                    // Get current year
                $currentYear = date('Y');

                    // Calculate used leaves for this employee, leave type and current year
                $usedLeaves = LeaveApplication::where('employee_id', $employeeId)
                    ->where('leave_type_id', $validated['leave_type_id'])
                    ->whereIn('status', ['approved', 'pending'])
                    ->whereYear('start_date', $currentYear)
                    ->sum('total_days');

                    // Check for overlapping leave applications
                $overlappingLeave = LeaveApplication::where('employee_id', $employeeId)
                    ->where(function ($query) use ($validated) {
                        $query
                            ->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                            ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                            ->orWhere(function ($q) use ($validated) {
                                $q->where('start_date', '<=', $validated['start_date'])->where('end_date', '>=', $validated['end_date']);
                            });
                    })
                    ->whereIn('status', ['approved', 'pending'])
                    ->first();

                if ($overlappingLeave) {
                    $startDate = \Carbon\Carbon::parse($overlappingLeave->start_date)->format('Y-m-d');
                    $endDate   = \Carbon\Carbon::parse($overlappingLeave->end_date)->format('Y-m-d');

                    return $this->errorResponse('Leave already applied for overlapping dates from ' . $startDate . ' to ' . $endDate);
                }

                    // Check if requested days exceed available balance
                $availableLeaves = $leaveType->max_days_per_year - $usedLeaves;
                if ($totalDays > $availableLeaves) {
                    return $this->errorResponse('Insufficient leave balance. Available Leave:' . $availableLeaves . ', ' . 'Requested Leave:' . $totalDays);
                }

                $leaveapplication                = new LeaveApplication();
                $leaveapplication->start_date    = $validated['start_date'];
                $leaveapplication->end_date      = $validated['end_date'];
                $leaveapplication->total_days    = $totalDays;
                $leaveapplication->reason        = $validated['reason'];
                $leaveapplication->status        = 'pending';
                $leaveapplication->employee_id   = $employeeId;
                $leaveapplication->leave_type_id = $validated['leave_type_id'];
                $leaveapplication->creator_id    = Auth::id();
                $leaveapplication->created_by    = $creatorId;
               

                    // Handle attachment image upload
                if ($request->hasFile('attachment')) {
                    $filenameWithExt = $request->file('attachment')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('attachment')->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                    $path = upload_file($request, 'attachment', $fileNameToStore, '');

                    if ($path['flag'] == 0) {
                        return $this->errorResponse($path['msg']);
                    }

                        // Delete old avatar if exists
                    if (!empty($leaveapplication->attachment) && strpos($leaveapplication->attachment, '') === false && getImageUrlPrefix($leaveapplication->attachment)) {
                        delete_file($leaveapplication->attachment);
                    }

                    $leaveapplication->attachment = ltrim($path['url'], '/');
                }
                $leaveapplication->save();

                if ($leaveapplication->attachment) {
                    $media = \App\Services\MediaAttachmentService::resolveOrBackfill(
                        $leaveapplication->attachment,
                        LeaveApplication::class,
                        $leaveapplication->id,
                        'leave_attachments',
                        Auth::id(),
                        $creatorId,
                        \App\Services\MediaAttachmentService::ensureDirectory('Leave Attachments', $creatorId, Auth::id())
                    );
                    if ($media) {
                        $leaveapplication->update(['media_id' => $media->id]);
                    }
                }

                return $this->successResponse(
                    (new LeaveApplicationResource($leaveapplication))->resolve(),
                    'Leave successfully created.'
                );
            } else {
                return $this->errorResponse('Permission denied');
            }
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong');
        }
    }
}
