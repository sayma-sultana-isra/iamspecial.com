document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.querySelectorAll('.like-btn');
    const viewLikesButtons = document.querySelectorAll('.view-likes-btn');
    const followButtons = document.querySelectorAll('.follow-btn');
    const deletePostButtons = document.querySelectorAll('.delete-post-btn');
    const commentButtons = document.querySelectorAll('.comment-btn');
    const submitCommentButtons = document.querySelectorAll('.submit-comment-btn');

    likeButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const postId = this.getAttribute('href').split('=')[1];
            const likeCountSpan = this.previousElementSibling;
            const likeButton = this;

            fetch(`like.php?post_id=${postId}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.action === 'liked') {
                    const likeCount = parseInt(likeCountSpan.textContent.split(' ')[0]) + 1;
                    likeCountSpan.textContent = `${likeCount} Likes`;
                    likeButton.textContent = 'Unlike';
                } else if (data.action === 'unliked') {
                    const likeCount = parseInt(likeCountSpan.textContent.split(' ')[0]) - 1;
                    likeCountSpan.textContent = `${likeCount} Likes`;
                    likeButton.textContent = 'Like';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    viewLikesButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const postId = this.getAttribute('data-post-id');
            const likesListContainer = document.getElementById(`likes-${postId}`);

            likesListContainer.style.display = likesListContainer.style.display === 'none' ? 'block' : 'none';

            if (likesListContainer.style.display === 'block') {
                if (likesListContainer.getAttribute('data-loaded') !== 'true') {
                    fetch(`likes.php?post_id=${postId}`)
                    .then(response => response.json())
                    .then(data => {
                        const likesList = likesListContainer.querySelector('.likes-list');
                        likesList.innerHTML = '';
                        data.likes.forEach(like => {
                            const likeDiv = document.createElement('div');
                            likeDiv.classList.add('like');
                            likeDiv.textContent = like.username;
                            likesList.appendChild(likeDiv);
                        });
                        likesListContainer.setAttribute('data-loaded', 'true');
                    })
                    .catch(error => console.error('Error:', error));
                }
            }
        });
    });

    followButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const userId = this.getAttribute('data-user-id');
            const followButton = this;
            const followerCountSpan = followButton.nextElementSibling;

            fetch(`follow.php?user_id=${userId}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.action === 'followed') {
                    followButton.textContent = 'Unfollow';
                    followerCountSpan.textContent = `${parseInt(followerCountSpan.textContent.split(' ')[0]) + 1} Followers`;
                } else if (data.action === 'unfollowed') {
                    followButton.textContent = 'Follow';
                    followerCountSpan.textContent = `${parseInt(followerCountSpan.textContent.split(' ')[0]) - 1} Followers`;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    deletePostButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const postId = this.getAttribute('data-post-id');
            const postElement = document.getElementById(`post-${postId}`);

            fetch(`delete_post.php?post_id=${postId}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    postElement.remove();
                } else {
                    alert('Failed to delete post');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    commentButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const postId = this.getAttribute('data-post-id');
            const commentsContainer = document.getElementById(`comments-${postId}`);

            commentsContainer.style.display = commentsContainer.style.display === 'none' ? 'block' : 'none';

            if (commentsContainer.style.display === 'block') {
                if (commentsContainer.getAttribute('data-loaded') !== 'true') {
                    fetch(`comments.php?post_id=${postId}`)
                    .then(response => response.json())
                    .then(data => {
                        const commentsList = commentsContainer.querySelector('.comments-list');
                        commentsList.innerHTML = '';
                        data.comments.forEach(comment => {
                            const commentDiv = document.createElement('div');
                            commentDiv.classList.add('comment');
                            commentDiv.innerHTML = `
                                <p><strong>${comment.username}</strong></p>
                                <p>${comment.comment}</p>
                                <div class="comment-actions">
                                    <button class="delete-comment-btn" data-comment-id="${comment.comment_id}">Delete</button>
                                </div>
                            `;
                            commentsList.appendChild(commentDiv);
                        });
                        commentsContainer.setAttribute('data-loaded', 'true');
                    })
                    .catch(error => console.error('Error:', error));
                }
            }
        });
    });

    submitCommentButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const postId = this.getAttribute('data-post-id');
            const commentTextarea = this.previousElementSibling;
            const commentContent = commentTextarea.value;

            if (commentContent.trim() !== '') {
                fetch(`submit_comment.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        post_id: postId,
                        comment: commentContent
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const commentsList = document.getElementById(`comments-${postId}`).querySelector('.comments-list');
                        const commentDiv = document.createElement('div');
                        commentDiv.classList.add('comment');
                        commentDiv.innerHTML = `
                            <p><strong>${data.comment.username}</strong></p>
                            <p>${data.comment.comment}</p>
                            <div class="comment-actions">
                                <button class="delete-comment-btn" data-comment-id="${data.comment.comment_id}">Delete</button>
                            </div>
                        `;
                        commentsList.appendChild(commentDiv);
                        commentTextarea.value = '';
                    } else {
                        alert('Failed to submit comment');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-comment-btn')) {
            event.preventDefault();
            const commentId = event.target.getAttribute('data-comment-id');

            fetch(`delete_comment.php?comment_id=${commentId}`, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    event.target.closest('.comment').remove();
                } else {
                    alert('Failed to delete comment');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});