

<?php $__env->startSection('title', 'Chat - PetrogenAI'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex h-screen bg-gray-50 overflow-hidden">
    <!-- Sidebar -->
    <div id="sidebar" class="w-80 bg-gray-900 text-white flex flex-col transition-all duration-300 ease-in-out">
        <!-- Sidebar Header -->
        <div class="p-4 border-b border-gray-800">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <span class="font-semibold text-lg">PetrogenAI</span>
                </div>
            </div>
            
            <!-- New Chat Button -->
            <button onclick="startNewChat()" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 rounded-lg transition duration-150">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="font-medium">New Chat</span>
            </button>
        </div>

        <!-- Conversations List -->
        <div class="flex-1 overflow-y-auto p-4 space-y-2">
            <div id="conversations-list">
                <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="conversation-item p-3 rounded-lg hover:bg-gray-800 cursor-pointer transition duration-150 <?php echo e($loop->first ? 'bg-gray-800' : ''); ?>"
                         data-id="<?php echo e($conversation->id); ?>"
                         onclick="loadConversation(<?php echo e($conversation->id); ?>)">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-200 truncate">
                                    <?php echo e($conversation->title ?? 'New Conversation'); ?>

                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    <?php echo e($conversation->updated_at->diffForHumans()); ?>

                                </p>
                            </div>
                            <button onclick="deleteConversation(<?php echo e($conversation->id); ?>, event)" 
                                    class="ml-2 text-gray-500 hover:text-red-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center text-gray-500 py-8">
                        <p class="text-sm">No conversations yet</p>
                        <p class="text-xs mt-1">Start a new chat to begin</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- User Profile -->
        <div class="p-4 border-t border-gray-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                        <span class="text-white font-semibold text-sm"><?php echo e(substr(auth()->user()->name, 0, 2)); ?></span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-200"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-xs text-gray-400"><?php echo e(auth()->user()->email); ?></p>
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="text-gray-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold text-gray-900" id="chat-title">New Conversation</h1>
                    <p class="text-sm text-gray-500">AI-powered assistant for Petrogen employees</p>
                </div>
                <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages Area -->
        <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-6">
            <!-- Welcome Message -->
            <div id="welcome-message" class="text-center py-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-full mb-6">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Welcome to PetrogenAI</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Your AI-powered assistant is ready to help. Ask questions, upload documents, and get intelligent responses powered by OpenAI.
                </p>
            </div>

            <!-- Messages will be inserted here -->
            <div id="messages"></div>
        </div>

        <!-- Input Area -->
        <div class="bg-white border-t border-gray-200 p-4">
            <form id="chat-form" class="max-w-4xl mx-auto">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="conversation-id" name="conversation_id" value="">
                
                <!-- File Attachments Preview -->
                <div id="attachments-preview" class="mb-3 hidden">
                    <div class="flex flex-wrap gap-2" id="attachment-items"></div>
                </div>

                <div class="flex items-end space-x-3">
                    <!-- File Upload Button -->
                    <div class="relative">
                        <input type="file" id="file-input" name="attachments[]" multiple class="hidden" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                        <button type="button" onclick="document.getElementById('file-input').click()" 
                                class="p-3 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                        </button>
                    </div>

                    <!-- Message Input -->
                    <div class="flex-1 relative">
                        <textarea id="message-input" name="message" rows="1" 
                                  placeholder="Type your message here..." 
                                  class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                  style="max-height: 200px;"></textarea>
                    </div>

                    <!-- Send Button -->
                    <button type="submit" id="send-button"
                            class="p-3 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-xl transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </div>

                <p class="text-xs text-gray-500 mt-2 text-center">
                    PetrogenAI may produce inaccurate information. Please verify important facts.
                </p>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    let currentConversationId = null;
    let selectedFiles = [];

    // Auto-resize textarea
    const messageInput = document.getElementById('message-input');
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Handle file selection
    document.getElementById('file-input').addEventListener('change', function(e) {
        selectedFiles = Array.from(e.target.files);
        updateAttachmentsPreview();
    });

    function updateAttachmentsPreview() {
        const preview = document.getElementById('attachments-preview');
        const items = document.getElementById('attachment-items');
        
        if (selectedFiles.length > 0) {
            preview.classList.remove('hidden');
            items.innerHTML = selectedFiles.map((file, index) => `
                <div class="flex items-center space-x-2 bg-gray-100 px-3 py-2 rounded-lg">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm text-gray-700">${file.name}</span>
                    <button type="button" onclick="removeFile(${index})" class="text-gray-500 hover:text-red-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `).join('');
        } else {
            preview.classList.add('hidden');
        }
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        updateFileInput();
        updateAttachmentsPreview();
    }

    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        document.getElementById('file-input').files = dt.files;
    }

    // Handle form submission
    document.getElementById('chat-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();
        
        if (!message) return;
        
        // Disable form
        const sendButton = document.getElementById('send-button');
        sendButton.disabled = true;
        messageInput.disabled = true;
        
        // Add user message to chat
        addMessage(message, 'user');
        
        // Clear input
        messageInput.value = '';
        messageInput.style.height = 'auto';
        
        // Hide welcome message
        document.getElementById('welcome-message')?.classList.add('hidden');
        
        // Prepare form data
        const formData = new FormData();
        formData.append('message', message);
        if (currentConversationId) {
            formData.append('conversation_id', currentConversationId);
        }
        
        // Add files
        selectedFiles.forEach(file => {
            formData.append('attachments[]', file);
        });
        
        // Show loading
        const loadingDiv = addMessage('Thinking...', 'assistant', true);
        
        try {
            const response = await fetch('<?php echo e(route("chat.store")); ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            });
            
            const data = await response.json();
            
            // Remove loading message
            loadingDiv.remove();
            
            if (data.success) {
                // Update conversation ID
                currentConversationId = data.conversation_id;
                document.getElementById('conversation-id').value = currentConversationId;
                
                // Add assistant response
                addMessage(data.assistant_message.content, 'assistant');
                
                // Clear attachments
                selectedFiles = [];
                document.getElementById('file-input').value = '';
                updateAttachmentsPreview();
                
                // Reload conversations list if new conversation
                if (!document.querySelector(`.conversation-item[data-id="${currentConversationId}"]`)) {
                    location.reload();
                }
            } else {
                addMessage('Sorry, there was an error processing your request. Please try again.', 'assistant');
            }
        } catch (error) {
            loadingDiv.remove();
            addMessage('Sorry, there was an error connecting to the server. Please try again.', 'assistant');
        }
        
        // Re-enable form
        sendButton.disabled = false;
        messageInput.disabled = false;
        messageInput.focus();
    });

    function addMessage(content, role, isLoading = false) {
        const messagesContainer = document.getElementById('messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${role === 'user' ? 'justify-end' : 'justify-start'}`;
        
        const bubbleClass = role === 'user' 
            ? 'bg-gradient-to-r from-blue-600 to-indigo-700 text-white' 
            : 'bg-white border border-gray-200 text-gray-900';
        
        const loadingIndicator = isLoading ? `
            <div class="flex space-x-1 mt-2">
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
            </div>
        ` : '';
        
        messageDiv.innerHTML = `
            <div class="max-w-3xl px-4 py-3 rounded-2xl ${bubbleClass} shadow-sm">
                <div class="prose prose-sm max-w-none">${escapeHtml(content)}</div>
                ${loadingIndicator}
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
        messageDiv.scrollIntoView({ behavior: 'smooth', block: 'end' });
        
        return messageDiv;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML.replace(/\n/g, '<br>');
    }

    function startNewChat() {
        currentConversationId = null;
        document.getElementById('conversation-id').value = '';
        document.getElementById('messages').innerHTML = '';
        document.getElementById('welcome-message')?.classList.remove('hidden');
        document.getElementById('chat-title').textContent = 'New Conversation';
        document.getElementById('message-input').focus();
        
        // Remove active class from all conversations
        document.querySelectorAll('.conversation-item').forEach(item => {
            item.classList.remove('bg-gray-800');
        });
    }

    async function loadConversation(id) {
        try {
            const response = await fetch(`/chat/${id}`);
            const data = await response.json();
            
            currentConversationId = id;
            document.getElementById('conversation-id').value = id;
            document.getElementById('chat-title').textContent = data.title || 'Conversation';
            document.getElementById('welcome-message')?.classList.add('hidden');
            
            // Clear messages
            const messagesContainer = document.getElementById('messages');
            messagesContainer.innerHTML = '';
            
            // Add messages
            data.messages.forEach(message => {
                addMessage(message.content, message.role);
            });
            
            // Update active conversation
            document.querySelectorAll('.conversation-item').forEach(item => {
                item.classList.remove('bg-gray-800');
            });
            document.querySelector(`.conversation-item[data-id="${id}"]`)?.classList.add('bg-gray-800');
            
        } catch (error) {
            console.error('Error loading conversation:', error);
        }
    }

    async function deleteConversation(id, event) {
        event.stopPropagation();
        
        if (!confirm('Are you sure you want to delete this conversation?')) {
            return;
        }
        
        try {
            const response = await fetch(`/chat/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            });
            
            if (response.ok) {
                location.reload();
            }
        } catch (error) {
            console.error('Error deleting conversation:', error);
        }
    }

    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
    }

    // Load first conversation if exists
    <?php if($conversations->isNotEmpty()): ?>
        loadConversation(<?php echo e($conversations->first()->id); ?>);
    <?php endif; ?>
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\salih\OneDrive\Desktop\SYSMNT\AI\Ai Projects\PetrogenAI\PetrogenAi\resources\views/chat/index.blade.php ENDPATH**/ ?>