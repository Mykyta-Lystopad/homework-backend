<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\MessageResourceCollection;
use App\Models\Answer;
use App\Models\Assignment;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    /**
     * Display a listing of current user resources.
     *
     * @return JsonResponse
     */
    public function myMessages()
    {
        $messages = request()->user()->myMessages()->paginate();
        return $this->success(MessageResourceCollection::make($messages));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MessageRequest $request
     * @return JsonResponse
     */
    public function store(MessageRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $target_user_id = $user->role === User::ROLE_STUDENT ? $user->id : $request->student_id;
        /** @var Answer $userAnswer */
        $userAnswer = Assignment::find(request()->assignment_id)->answers()->firstOrCreate(['user_id' => $target_user_id]);
        $message = Message::make($request->validated());
        $userAnswer->messages()->save($message);
        return $this->created(MessageResource::make($message->load('user')));
    }

    /**
     * Display the specified resource.
     *
     * @param Message $message
     * @return JsonResponse
     */
    public function show(Message $message)
    {
        return $this->success(MessageResource::make($message->load('user')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MessageRequest $request
     * @param Message $message
     * @return JsonResponse
     */
    public function update(MessageRequest $request, Message $message)
    {
        $message->update($request->validated());
        return $this->success(MessageResource::make($message->load('user')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Message $message
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Message $message)
    {
        $message->delete();
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }
}
