<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationCollection;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $service)
    {
        $this->middleware(['auth:sanctum', 'admin']);
    }

    public function index(): NotificationCollection
    {
        ['page' => $page, 'perPage' => $perPage] = getPageVar();
        return new NotificationCollection($this->service->paginate($page, $perPage));
    }

    /**
     * Mark a notification as read
     *
     * @param int $id Notification identifier
     * @return Response
     */
    public function markAsRead(Request $request): Response
    {
        $data = $request->validate($this->rules());
        $this->service->markAsRead(Arr::flatten($data));
        return response('OK');
    }

    public function unreadNotificationCount(): JsonResponse
    {
        return response()->json(['count' => $this->service->unreadCount()]);
    }

    /**
     * Rules to make notifications as read
     *
     * @return string[]
     */
    private function rules(): array
    {
        return [
            'notifications' => 'required|array|min:1',
            'notifications.*.id' => 'required|numeric|exists:notifications,id'
        ];
    }
}
