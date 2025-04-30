/**
 * Todo List Functionality
 */
$(document).ready(function() {
    // Initialize todo list
    initTodoList();
    
    // Initialize Add Task form
    initAddTaskForm();
    
    /**
     * Initialize todo list item functionality
     */
    function initTodoList() {
        // Task checkbox change event
        $('.task-checkbox').on('change', function() {
            const taskId = $(this).data('task-id');
            const todoItem = $(this).closest('.todo-item');
            
            if (this.checked) {
                // Add loading indicator
                todoItem.addClass('opacity-50');
                
                // AJAX request to update task status to completed
                $.ajax({
                    url: '/tasks/' + taskId + '/complete',
                    type: 'PUT',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'PUT'
                    },
                    success: function(response) {
                        // Fade out and remove the todo item
                        todoItem.fadeOut(500, function() {
                            $(this).remove();
                            
                            // Show empty message if no items left
                            if ($('.todo-item').length === 0) {
                                $('.todo-list-container').append('<div class="text-center py-3"><p>No pending tasks available</p></div>');
                            }
                            
                            // Update the pending tasks count
                            updatePendingTasksCount();
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating task:', error);
                        // Remove loading indicator and uncheck the checkbox
                        todoItem.removeClass('opacity-50');
                        $('.task-checkbox[data-task-id="' + taskId + '"]').prop('checked', false);
                        
                        // Show error message
                        alert('Could not complete the task. Please try again.');
                    }
                });
            }
        });
    }
    
    /**
     * Initialize Add Task form
     */
    function initAddTaskForm() {
        // Submit new task form
        $('#addTaskForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = $(this).serialize();
            
            // Disable submit button and show loading
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
            
            // AJAX request to create new task
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Close modal
                    $('#add_todo').modal('hide');
                    
                    // Reset form
                    $('#addTaskForm')[0].reset();
                    
                    // Reload dashboard to show new task
                    window.location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error creating task:', error);
                    
                    // Show error message
                    let errorMessage = 'Failed to create task. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                    
                    // Display error in form
                    $('#addTaskForm').prepend(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${errorMessage}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `);
                },
                complete: function() {
                    // Re-enable submit button
                    submitBtn.prop('disabled', false).text('Save Task');
                }
            });
        });
        
        // Clear form when modal is hidden
        $('#add_todo').on('hidden.bs.modal', function () {
            $('#addTaskForm')[0].reset();
            $('#addTaskForm .alert').remove();
        });
    }
    
    /**
     * Update the pending tasks count in the dashboard card
     */
    function updatePendingTasksCount() {
        // Update pending tasks count via AJAX
        $.ajax({
            url: '/dashboard/pending-tasks-count',
            type: 'GET',
            success: function(response) {
                // Update the counter in the card
                $('.text-warning').closest('.card').find('.h5').text(response.count);
            }
        });
    }
});