notification['date']) ?></small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <?php if ($notification['status'] === 'unread'): ?>
                                            <button class="btn btn-sm btn-outline-primary" onclick="markAsRead(this)">
                                                <i class="bi bi-check2"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteNotification(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Settings Modal -->
    <div class="modal fade" id="notificationSettingsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Zaawansowane ustawienia powiadomień</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="advancedNotificationSettings">
                        <div class="mb-3">
                            <label class="form-label">Priorytet powiadomień</label>
                            <select class="form-select">
                                <option value="high">Wysoki</option>
                                <option value="medium">Średni</option>
                                <option value="low">Niski</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Format powiadomień</label>
                            <select class="form-select">
                                <option value="detailed">Szczegółowy</option>
                                <option value="summary">Podsumowanie</option>
                                <option value="minimal">Minimalny</option>
                            </select>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="groupNotifications">
                            <label class="form-check-label" for="groupNotifications">
                                Grupuj podobne powiadomienia
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                    <button type="button" class="btn btn-primary">Zapisz ustawienia</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form submission handlers
        document.getElementById('notificationSettingsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveNotificationSettings();
        });

        document.getElementById('notificationScheduleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveScheduleSettings();
        });

        // Notification management functions
        function markAsRead(button) {
            const notificationItem = button.closest('.notification-item');
            notificationItem.classList.remove('unread');
            button.remove();
            updateNotificationCount();
        }

        function deleteNotification(button) {
            if (confirm('Czy na pewno chcesz usunąć to powiadomienie?')) {
                const notificationItem = button.closest('.notification-item');
                notificationItem.remove();
                updateNotificationCount();
            }
        }

        function markAllAsRead() {
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
                item.querySelector('.btn-outline-primary')?.remove();
            });
            updateNotificationCount();
        }

        function updateNotificationCount() {
            const unreadCount = document.querySelectorAll('.notification-item.unread').length;
            const badge = document.querySelector('.notification-badge');
            if (unreadCount > 0) {
                badge.textContent = unreadCount;
                badge.style.display = 'inline';
            } else {
                badge.style.display = 'none';
            }
        }

        function saveNotificationSettings() {
            // Implementacja zapisywania ustawień powiadomień
            alert('Ustawienia powiadomień zostały zapisane!');
        }

        function saveScheduleSettings() {
            // Implementacja zapisywania harmonogramu
            alert('Harmonogram powiadomień został zapisany!');
        }

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>
