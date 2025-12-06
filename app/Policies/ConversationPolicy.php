<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    public function view(User $user, Conversation $conversation)
    {
        return $user->id === $conversation->user_id || $user->isAdmin();
    }

    public function delete(User $user, Conversation $conversation)
    {
        return $user->id === $conversation->user_id || $user->isAdmin();
    }
}
