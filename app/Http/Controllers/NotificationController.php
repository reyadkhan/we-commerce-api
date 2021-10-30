<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationCollection;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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
    public function markAsRead(int $id): Response
    {
        $this->service->markAsRead($id);
        return response('OK');
    }

    public function unreadNotificationCount(): JsonResponse
    {
        return response()->json(['count' => $this->service->unreadCount()]);
    }
}
